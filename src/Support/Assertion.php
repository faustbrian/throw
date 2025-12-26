<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Support;

use Throwable;

use function abort_if;
use function abort_unless;
use function throw_if;

/**
 * Fluent assertion builder for conditional throwing.
 *
 * This class provides a fluent interface for making assertions and conditionally
 * throwing exceptions or aborting HTTP requests when those assertions fail. It offers
 * a more readable alternative to traditional guard clauses and enables expressive
 * error handling in both application logic and HTTP contexts.
 *
 * The class is immutable (readonly) and provides three types of methods:
 * - Conditional exception throwing (orThrow, throwIf, throwUnless)
 * - Generic HTTP abort methods (orAbort, abortIf, abortUnless)
 * - Convenience HTTP status methods (orNotFound, orForbidden, etc.)
 *
 * All methods evaluate the assertion condition and trigger their respective
 * actions only when the condition fails (or succeeds, for *If variants).
 *
 * @example Basic assertion
 * ```php
 * ensure($user !== null)->orThrow(UserNotFoundException::class);
 * ```
 * @example HTTP abort with enum
 * ```php
 * ensure($user->isAdmin())->orAbort(HttpStatusCode::Forbidden);
 * ```
 * @example With custom exception instance
 * ```php
 * ensure($token->isValid())->orThrow(InvalidTokenException::expired());
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 *
 * @psalm-immutable
 *
 * @see ensure() Global helper function for creating assertions
 * @see HttpStatusCode Enum for type-safe HTTP status codes
 */
final readonly class Assertion
{
    /**
     * Create a new assertion instance.
     *
     * @param bool $condition The boolean condition to evaluate for assertions.
     *                        When true, orThrow/orAbort methods will not trigger.
     *                        When false, the specified exception will be thrown or
     *                        the HTTP request will be aborted with the given status code.
     */
    public function __construct(
        private bool $condition,
    ) {}

    /**
     * Throw an exception if the assertion fails.
     *
     * If the condition is false, throws the specified exception. The exception
     * can be provided as a class name string or as an instantiated exception.
     *
     * @param class-string<Throwable>|Throwable $exception Exception class or instance to throw
     * @param null|string                       $message   Optional message (only used with class string)
     *
     * @throws Throwable When the condition is false
     *
     * @example With exception class string
     * ```php
     * ensure($user !== null)->orThrow(UserNotFoundException::class);
     * ```
     * @example With exception class string and message
     * ```php
     * ensure($email !== null)->orThrow(ValidationException::class, 'Email is required');
     * ```
     * @example With exception instance
     * ```php
     * ensure($token->isValid())
     *     ->orThrow(InvalidTokenException::expired());
     * ```
     */
    public function orThrow(string|Throwable $exception, ?string $message = null): void
    {
        if (!$this->condition) {
            throw_if($exception instanceof Throwable, $exception);

            throw new $exception($message ?? '');
        }
    }

    /**
     * Throw an exception if the condition is true.
     *
     * Inverse of orThrow - throws when the assertion condition is true.
     *
     * @param class-string<Throwable>|Throwable $exception Exception class or instance to throw
     * @param null|string                       $message   Optional message (only used with class string)
     *
     * @throws Throwable When the condition is true
     *
     * @example
     * ```php
     * ensure($user->isBanned())->throwIf(UserBannedException::class);
     * ```
     */
    public function throwIf(string|Throwable $exception, ?string $message = null): void
    {
        if (!$this->condition) {
            return;
        }

        throw_if($exception instanceof Throwable, $exception);

        throw new $exception($message ?? '');
    }

    /**
     * Throw an exception unless the condition is true.
     *
     * Alias for orThrow - throws when the assertion condition is false.
     *
     * @param class-string<Throwable>|Throwable $exception Exception class or instance to throw
     * @param null|string                       $message   Optional message (only used with class string)
     *
     * @throws Throwable When the condition is false
     *
     * @example
     * ```php
     * ensure($user !== null)->throwUnless(UserNotFoundException::class);
     * ```
     */
    public function throwUnless(string|Throwable $exception, ?string $message = null): void
    {
        if (!$this->condition) {
            throw_if($exception instanceof Throwable, $exception);

            throw new $exception($message ?? '');
        }
    }

    /**
     * Abort the request if the assertion fails.
     *
     * If the condition is false, aborts the request with the specified HTTP
     * status code and optional message.
     *
     * @param HttpStatusCode $code    HTTP status code
     * @param null|string    $message Optional message
     *
     * @example Abort with status code enum
     * ```php
     * ensure($user->can('admin'))->orAbort(HttpStatusCode::Forbidden);
     * ```
     * @example Abort with custom message
     * ```php
     * ensure($post !== null)->orAbort(HttpStatusCode::NotFound, 'Post not found');
     * ```
     */
    public function orAbort(HttpStatusCode $code, ?string $message = null): void
    {
        abort_unless($this->condition, $code->value, $message ?? '');
    }

    /**
     * Abort the request if the condition is true.
     *
     * Inverse of orAbort - aborts when the assertion condition is true.
     *
     * @param HttpStatusCode $code    HTTP status code
     * @param null|string    $message Optional message
     *
     * @example
     * ```php
     * ensure($user->isBanned())->abortIf(HttpStatusCode::Forbidden, 'Account banned');
     * ```
     */
    public function abortIf(HttpStatusCode $code, ?string $message = null): void
    {
        abort_if($this->condition, $code->value, $message ?? '');
    }

    /**
     * Abort the request unless the condition is true.
     *
     * Alias for orAbort - aborts when the assertion condition is false.
     *
     * @param HttpStatusCode $code    HTTP status code
     * @param null|string    $message Optional message
     *
     * @example
     * ```php
     * ensure($user !== null)->abortUnless(HttpStatusCode::NotFound);
     * ```
     */
    public function abortUnless(HttpStatusCode $code, ?string $message = null): void
    {
        abort_unless($this->condition, $code->value, $message ?? '');
    }

    /**
     * Abort with 400 Bad Request if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($input->isValid())->orBadRequest('Invalid input provided');
     * ```
     */
    public function orBadRequest(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::BadRequest, $message);
    }

    /**
     * Abort with 401 Unauthorized if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($token !== null)->orUnauthorized('Authentication required');
     * ```
     */
    public function orUnauthorized(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::Unauthorized, $message);
    }

    /**
     * Abort with 403 Forbidden if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($user->can('admin'))->orForbidden();
     * ```
     */
    public function orForbidden(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::Forbidden, $message);
    }

    /**
     * Abort with 404 Not Found if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($post !== null)->orNotFound();
     * ```
     */
    public function orNotFound(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::NotFound, $message);
    }

    /**
     * Abort with 409 Conflict if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure(!$user->exists())->orConflict('User already exists');
     * ```
     */
    public function orConflict(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::Conflict, $message);
    }

    /**
     * Abort with 422 Unprocessable Entity if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($validation->passes())->orUnprocessable('Validation failed');
     * ```
     */
    public function orUnprocessable(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::UnprocessableEntity, $message);
    }

    /**
     * Abort with 429 Too Many Requests if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($rateLimiter->allow())->orTooManyRequests();
     * ```
     */
    public function orTooManyRequests(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::TooManyRequests, $message);
    }

    /**
     * Abort with 500 Internal Server Error if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($service->isHealthy())->orServerError();
     * ```
     */
    public function orServerError(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::InternalServerError, $message);
    }

    /**
     * Abort with 405 Method Not Allowed if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($request->isMethod('POST'))->orMethodNotAllowed();
     * ```
     */
    public function orMethodNotAllowed(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::MethodNotAllowed, $message);
    }

    /**
     * Abort with 406 Not Acceptable if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($request->accepts('application/json'))->orNotAcceptable();
     * ```
     */
    public function orNotAcceptable(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::NotAcceptable, $message);
    }

    /**
     * Abort with 408 Request Timeout if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($request->timedOut())->orRequestTimeout();
     * ```
     */
    public function orRequestTimeout(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::RequestTimeout, $message);
    }

    /**
     * Abort with 410 Gone if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure(!$resource->isDeleted())->orGone('Resource permanently deleted');
     * ```
     */
    public function orGone(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::Gone, $message);
    }

    /**
     * Abort with 413 Payload Too Large if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($file->size() <= $maxSize)->orPayloadTooLarge();
     * ```
     */
    public function orPayloadTooLarge(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::PayloadTooLarge, $message);
    }

    /**
     * Abort with 415 Unsupported Media Type if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($request->isJson())->orUnsupportedMediaType();
     * ```
     */
    public function orUnsupportedMediaType(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::UnsupportedMediaType, $message);
    }

    /**
     * Abort with 418 I'm a teapot if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure(!$request->wantsCoffee())->orImATeapot();
     * ```
     */
    public function orImATeapot(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::ImATeapot, $message);
    }

    /**
     * Abort with 423 Locked if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure(!$resource->isLocked())->orLocked('Resource is locked');
     * ```
     */
    public function orLocked(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::Locked, $message);
    }

    /**
     * Abort with 428 Precondition Required if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($request->hasHeader('If-Match'))->orPreconditionRequired();
     * ```
     */
    public function orPreconditionRequired(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::PreconditionRequired, $message);
    }

    /**
     * Abort with 501 Not Implemented if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($feature->isImplemented())->orNotImplemented();
     * ```
     */
    public function orNotImplemented(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::NotImplemented, $message);
    }

    /**
     * Abort with 502 Bad Gateway if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($upstream->isResponding())->orBadGateway();
     * ```
     */
    public function orBadGateway(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::BadGateway, $message);
    }

    /**
     * Abort with 503 Service Unavailable if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure(!$maintenance->isEnabled())->orServiceUnavailable();
     * ```
     */
    public function orServiceUnavailable(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::ServiceUnavailable, $message);
    }

    /**
     * Abort with 504 Gateway Timeout if the assertion fails.
     *
     * @param null|string $message Optional message
     *
     * @example
     * ```php
     * ensure($upstream->respondedInTime())->orGatewayTimeout();
     * ```
     */
    public function orGatewayTimeout(?string $message = null): void
    {
        $this->orAbort(HttpStatusCode::GatewayTimeout, $message);
    }
}
