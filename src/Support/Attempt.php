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
use Closure;
use InvalidArgumentException;
use Throwable;

use function abort_if;
use function assert;
use function class_exists;
use function is_callable;
use function is_object;
use function is_string;
use function method_exists;
use function sprintf;
use function throw_if;

/**
 * Scala-inspired Try monad for exception handling.
 *
 * Represents a computation that may either result in a Success or Failure.
 * Provides a fluent API for handling exceptions through various strategies
 * like transforming errors, providing defaults, or aborting HTTP requests.
 *
 * @template TResult
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
 */
final readonly class Attempt
{
    /**
     * @param null|TResult $result
     */
    public function __construct(
        private mixed $result,
        private ?Throwable $exception,
    ) {}

    /**
     * Create an Attempt instance by executing a callable.
     *
     * @template T
     *
     * @param  array{class-string|object, string}|callable(): T|class-string|object $callable The code to execute
     * @return self<T>
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
     * @param array{class-string|object, string}|callable|class-string|object $callable
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

            throw new InvalidArgumentException(sprintf('Class %s must have __invoke or handle method', $callable));
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

            throw new InvalidArgumentException('Object must have __invoke or handle method');
        }

        throw new InvalidArgumentException('Invalid callable provided');
    }
}
