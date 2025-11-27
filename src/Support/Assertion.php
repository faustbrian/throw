<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Support;

use Throwable;

use function abort;
use function throw_if;

/**
 * Fluent assertion builder for conditional throwing.
 *
 * This class provides a fluent interface for making assertions and conditionally
 * throwing exceptions or aborting requests when those assertions fail. It offers
 * a more readable alternative to traditional guard clauses.
 *
 * @example Basic assertion
 * ```php
 * ensure($user !== null)->orThrow(UserNotFoundException::class);
 * ```
 * @example HTTP abort
 * ```php
 * ensure($user->isAdmin())->orAbort(403);
 * ```
 * @example With custom exception instance
 * ```php
 * ensure($token->isValid())->orThrow(InvalidTokenException::expired());
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 *
 * @psalm-immutable
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
        if ($this->condition) {
            return;
        }

        throw_if($exception instanceof Throwable, $exception);

        throw new $exception($message ?? '');
    }

    /**
     * Abort the request if the assertion fails.
     *
     * If the condition is false, aborts the request with the specified HTTP
     * status code and optional message.
     *
     * @param int         $code    HTTP status code
     * @param null|string $message Optional message
     *
     * @example Abort with 403 Forbidden
     * ```php
     * ensure($user->can('admin'))->orAbort(403);
     * ```
     * @example Abort with custom message
     * ```php
     * ensure($post !== null)->orAbort(404, 'Post not found');
     * ```
     */
    public function orAbort(int $code, ?string $message = null): void
    {
        if ($this->condition) {
            return;
        }

        abort($code, $message ?? '');
    }
}
