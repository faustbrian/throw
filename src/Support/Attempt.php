<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Support;

use Cline\Monad\Option\None;
use Cline\Monad\Option\Option;
use Cline\Monad\Option\Some;
use Cline\Monad\Result\Err;
use Cline\Monad\Result\Ok;
use Cline\Monad\Result\Result;
use Cline\Throw\Exceptions\ClassMissingCallableMethodException;
use Cline\Throw\Exceptions\InvalidCallableException;
use Cline\Throw\Exceptions\ObjectMissingCallableMethodException;
use Closure;
use Throwable;

use function abort_if;
use function assert;
use function class_exists;
use function is_callable;
use function is_object;
use function is_string;
use function method_exists;
use function throw_if;

/**
 * Scala-inspired Try monad for exception handling.
 *
 * Represents a computation that may either result in a Success (containing a value)
 * or Failure (containing an exception). This class provides a functional approach to
 * exception handling, allowing you to work with potentially failing operations without
 * explicit try-catch blocks.
 *
 * The class is immutable (readonly) and provides multiple strategies for handling failures:
 * - Unwrap results with get() or orThrow()
 * - Provide default values with getOrElse()
 * - Recover from errors with recover()
 * - Convert to other monads (Option, Result)
 * - Abort HTTP requests on failure
 *
 * All methods are side-effect free until you explicitly unwrap the value or trigger
 * an abort/throw operation. This enables safe composition of potentially failing operations.
 *
 * @example Execute and unwrap with get()
 * ```php
 * attempt(fn() => User::findOrFail($id))->get();
 * ```
 * @example Execute with default fallback
 * ```php
 * $user = attempt(fn() => User::find($id))->getOrElse(null);
 * ```
 * @example Convert to Option monad
 * ```php
 * $user = attempt(fn() => loadUser())->toOption(); // Some<User> or None
 * ```
 * @example Recover from failure with callback
 * ```php
 * attempt(fn() => api())->recover(fn($e) => cached());
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 *
 * @psalm-immutable
 *
 * @see attempt() Global helper function for creating Attempt instances
 * @see Option For representing optional values
 * @see Result For representing success/failure with typed errors
 * @template TResult The type of the successful computation result
 */
final readonly class Attempt
{
    /**
     * Create a new Attempt instance with a result or exception.
     *
     * This constructor is private to enforce using the of() factory method
     * for creating instances. It stores either a successful result or the
     * exception that was thrown during execution.
     *
     * @internal Use the of() factory method or attempt() helper instead
     * @param null|TResult   $result    The successful result, or null if execution failed
     * @param null|Throwable $exception The exception if execution failed, or null if successful
     */
    public function __construct(
        private mixed $result,
        private ?Throwable $exception,
    ) {}

    /**
     * Create an Attempt instance by executing a callable.
     *
     * This factory method accepts various callable formats and executes them,
     * capturing either the successful result or any thrown exception. Supports:
     * - Closures and regular callables
     * - Class strings (must have __invoke or handle method)
     * - Object instances (must have __invoke or handle method)
     * - Array callables [object|class-string, 'methodName']
     *
     * @template T
     *
     * @param array{class-string|object, string}|callable(): T|class-string|object $callable The code to execute
     *
     * @return self<T> An Attempt instance containing either the result or exception
     *
     * @example With closure
     * ```php
     * Attempt::of(fn() => User::findOrFail($id));
     * ```
     * @example With invokable class
     * ```php
     * Attempt::of(ProcessPayment::class);
     * ```
     * @example With object instance
     * ```php
     * Attempt::of(new ProcessPayment($amount));
     * ```
     */
    public static function of(callable|string|object|array $callable): self
    {
        $invokable = self::resolveCallable($callable);

        try {
            /** @var T $result */
            $result = $invokable();

            return new self(result: $result, exception: null);
        } catch (Throwable $throwable) {
            /** @var self<T> */
            return new self(result: null, exception: $throwable);
        }
    }

    /**
     * Get the result or throw the original exception.
     *
     * Scala-style unwrap - throws the original exception if execution failed.
     *
     * @throws Throwable When execution failed
     *
     * @return TResult
     *
     * @example Unwrap result
     * ```php
     * $user = attempt(fn() => User::findOrFail($id))->get();
     * ```
     */
    public function get(): mixed
    {
        if ($this->exception instanceof Throwable) {
            throw $this->exception;
        }

        assert($this->result !== null);

        return $this->result;
    }

    /**
     * Throw a custom exception if execution failed.
     *
     * @param class-string<Throwable>|Throwable $exception Exception class or instance to throw
     * @param null|string                       $message   Optional message (only used with class string)
     *
     * @throws Throwable When execution failed
     *
     * @return TResult
     *
     * @example With exception class string
     * ```php
     * attempt(fn() => User::find($id))->orThrow(NotFoundException::class);
     * ```
     * @example With exception instance
     * ```php
     * attempt(fn() => parseJson($data))->orThrow(JsonException::invalidSyntax());
     * ```
     */
    public function orThrow(string|Throwable $exception, ?string $message = null): mixed
    {
        if ($this->exception instanceof Throwable) {
            throw_if($exception instanceof Throwable, $exception);

            throw new $exception($message ?? $this->exception->getMessage(), previous: $this->exception);
        }

        assert($this->result !== null);

        return $this->result;
    }

    /**
     * Abort the HTTP request if execution failed.
     *
     * @param  HttpStatusCode $code    HTTP status code
     * @param  null|string    $message Optional message
     * @return TResult
     *
     * @example Abort with status code
     * ```php
     * try(fn() => authorize($user))->abort(HttpStatusCode::Forbidden);
     * ```
     */
    public function abort(HttpStatusCode $code, ?string $message = null): mixed
    {
        abort_if($this->exception instanceof Throwable, $code->value, $message ?? $this->exception?->getMessage() ?? '');

        assert($this->result !== null);

        return $this->result;
    }

    /**
     * Convert to Option monad (Some or None).
     *
     * @return Option<TResult>
     *
     * @example Convert to Option
     * ```php
     * $user = attempt(fn() => User::find($id))->toOption();
     * // Returns Some<User> or None
     * ```
     * @example Chain with Option methods
     * ```php
     * $name = attempt(fn() => loadUser())
     *     ->toOption()
     *     ->map(fn($user) => $user->name)
     *     ->unwrapOr('Guest');
     * ```
     */
    public function toOption(): Option
    {
        if ($this->exception instanceof Throwable) {
            return None::create();
        }

        /** @var TResult $result */
        $result = $this->result;

        return new Some($result);
    }

    /**
     * Convert to Result monad (Ok or Err).
     *
     * @return Result<TResult, Throwable>
     *
     * @example Convert to Result
     * ```php
     * $result = attempt(fn() => User::findOrFail($id))->toResult();
     * // Returns Ok<User> or Err<Throwable>
     * ```
     * @example Chain with Result methods
     * ```php
     * $user = attempt(fn() => loadUser())
     *     ->toResult()
     *     ->map(fn($user) => $user->name)
     *     ->unwrapOr('Guest');
     * ```
     * @example Handle errors explicitly
     * ```php
     * $result = attempt(fn() => processPayment())
     *     ->toResult()
     *     ->mapErr(fn($e) => logger()->error($e))
     *     ->unwrapOrElse(fn($e) => null);
     * ```
     */
    public function toResult(): Result
    {
        if ($this->exception instanceof Throwable) {
            return new Err($this->exception);
        }

        /** @var TResult $result */
        $result = $this->result;

        return new Ok($result);
    }

    /**
     * Return a default value if execution failed.
     *
     * @template TDefault
     *
     * @param  TDefault         $default The default value to return on failure
     * @return TDefault|TResult
     *
     * @example Return default value
     * ```php
     * $config = try(fn() => loadConfig())->getOrElse([]);
     * ```
     */
    public function getOrElse(mixed $default): mixed
    {
        if ($this->exception instanceof Throwable) {
            return $default;
        }

        assert($this->result !== null);

        return $this->result;
    }

    /**
     * Execute a rescue callback if execution failed.
     *
     * @template TRescue
     *
     * @param  callable(Throwable): TRescue $rescue Callback to handle the exception
     * @return TRescue|TResult
     *
     * @example Rescue with callback
     * ```php
     * try(fn() => processPayment())->recover(fn($e) => logError($e));
     * ```
     */
    public function recover(callable $rescue): mixed
    {
        if ($this->exception instanceof Throwable) {
            return $rescue($this->exception);
        }

        assert($this->result !== null);

        return $this->result;
    }

    /**
     * Abort with 400 Bad Request if execution failed.
     *
     * @param  null|string $message Optional message
     * @return TResult
     *
     * @example
     * ```php
     * try(fn() => validateInput($data))->orBadRequest();
     * ```
     */
    public function orBadRequest(?string $message = null): mixed
    {
        return $this->abort(HttpStatusCode::BadRequest, $message);
    }

    /**
     * Abort with 401 Unauthorized if execution failed.
     *
     * @param  null|string $message Optional message
     * @return TResult
     *
     * @example
     * ```php
     * try(fn() => authenticate($token))->orUnauthorized();
     * ```
     */
    public function orUnauthorized(?string $message = null): mixed
    {
        return $this->abort(HttpStatusCode::Unauthorized, $message);
    }

    /**
     * Abort with 403 Forbidden if execution failed.
     *
     * @param  null|string $message Optional message
     * @return TResult
     *
     * @example
     * ```php
     * try(fn() => $user->deleteAccount())->orForbidden();
     * ```
     */
    public function orForbidden(?string $message = null): mixed
    {
        return $this->abort(HttpStatusCode::Forbidden, $message);
    }

    /**
     * Abort with 404 Not Found if execution failed.
     *
     * @param  null|string $message Optional message
     * @return TResult
     *
     * @example
     * ```php
     * try(fn() => Post::findOrFail($id))->orNotFound();
     * ```
     */
    public function orNotFound(?string $message = null): mixed
    {
        return $this->abort(HttpStatusCode::NotFound, $message);
    }

    /**
     * Abort with 409 Conflict if execution failed.
     *
     * @param  null|string $message Optional message
     * @return TResult
     *
     * @example
     * ```php
     * try(fn() => User::create($data))->orConflict();
     * ```
     */
    public function orConflict(?string $message = null): mixed
    {
        return $this->abort(HttpStatusCode::Conflict, $message);
    }

    /**
     * Abort with 422 Unprocessable Entity if execution failed.
     *
     * @param  null|string $message Optional message
     * @return TResult
     *
     * @example
     * ```php
     * try(fn() => validator($data)->validate())->orUnprocessable();
     * ```
     */
    public function orUnprocessable(?string $message = null): mixed
    {
        return $this->abort(HttpStatusCode::UnprocessableEntity, $message);
    }

    /**
     * Abort with 429 Too Many Requests if execution failed.
     *
     * @param  null|string $message Optional message
     * @return TResult
     *
     * @example
     * ```php
     * try(fn() => rateLimiter()->attempt())->orTooManyRequests();
     * ```
     */
    public function orTooManyRequests(?string $message = null): mixed
    {
        return $this->abort(HttpStatusCode::TooManyRequests, $message);
    }

    /**
     * Abort with 500 Internal Server Error if execution failed.
     *
     * @param  null|string $message Optional message
     * @return TResult
     *
     * @example
     * ```php
     * try(fn() => processData())->orServerError();
     * ```
     */
    public function orServerError(?string $message = null): mixed
    {
        return $this->abort(HttpStatusCode::InternalServerError, $message);
    }

    /**
     * Resolve the callable from various input formats.
     *
     * Converts different callable representations into a Closure for execution.
     * Handles class strings by instantiating them and checking for __invoke or
     * handle methods. For object instances, checks for the same methods.
     *
     * @param array{class-string|object, string}|callable|class-string|object $callable The callable to resolve
     *
     * @throws ClassMissingCallableMethodException  If class string lacks __invoke or handle method
     * @throws InvalidCallableException             If the input cannot be resolved to a callable
     * @throws ObjectMissingCallableMethodException If object lacks __invoke or handle method
     *
     * @return Closure The resolved callable as a Closure
     */
    private static function resolveCallable(callable|string|object|array $callable): Closure
    {
        // Already a closure or callable
        if ($callable instanceof Closure || is_callable($callable)) {
            return $callable(...);
        }

        // Class string - check for __invoke or handle method
        if (is_string($callable) && class_exists($callable)) {
            $instance = new $callable();

            if (method_exists($instance, '__invoke')) {
                return $instance(...);
            }

            if (method_exists($instance, 'handle')) {
                /** @phpstan-ignore-next-line Creating callable from handle() method for invokable pattern support */
                return $instance->handle(...);
            }

            throw ClassMissingCallableMethodException::forClass($callable);
        }

        // Object instance - check for __invoke or handle method
        if (is_object($callable)) {
            if (method_exists($callable, '__invoke')) {
                return $callable(...);
            }

            if (method_exists($callable, 'handle')) {
                /** @phpstan-ignore-next-line Creating callable from handle() method for invokable pattern support */
                return $callable->handle(...);
            }

            throw ObjectMissingCallableMethodException::create();
        }

        throw InvalidCallableException::create();
    }
}
