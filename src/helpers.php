<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw;

use Cline\Throw\Exceptions\ExceptionGroup;
use Cline\Throw\Support\Assertion;
use Cline\Throw\Support\Attempt;
use Cline\Throw\Support\DeferredCleanup;
use Throwable;

use function function_exists;
use function value;

if (!function_exists('Cline\Throw\ensure')) {
    /**
     * Create a fluent assertion that can throw or abort on failure.
     *
     * This helper provides a readable way to make assertions about values
     * and conditionally throw exceptions or abort HTTP requests when the
     * assertion fails.
     *
     * @param bool|callable $condition The condition to assert (bool or callable returning bool)
     *
     * @example Throw on null value
     * ```php
     * ensure($user !== null)->orThrow(UserNotFoundException::class);
     * ```
     * @example Throw with custom exception
     * ```php
     * ensure($token->isValid())
     *     ->orThrow(InvalidTokenException::expired());
     * ```
     * @example Abort HTTP request
     * ```php
     * ensure($user->isAdmin())->orAbort(HttpStatusCode::Forbidden);
     * ```
     * @example Guard against invalid state
     * ```php
     * ensure($order->canBeCancelled())
     *     ->orThrow(OrderException::cannotCancel());
     * ```
     * @example Lazy evaluation with callback
     * ```php
     * ensure(fn() => $user->isAdmin())->orForbidden();
     * ensure(fn() => expensive_check())->orThrow(Exception::class);
     * ```
     */
    function ensure(bool|callable $condition): Assertion
    {
        /** @var bool $resolved */
        $resolved = value($condition);

        return new Assertion($resolved);
    }
}

if (!function_exists('Cline\Throw\attempt')) {
    /**
     * Execute code and handle exceptions fluently (Scala-style Try monad).
     *
     * This helper provides a Scala-inspired API for executing code that may
     * throw exceptions and handling those exceptions with various strategies.
     *
     * @template T
     *
     * @param  array{class-string|object, string}|callable(): T|class-string|object $callable The code to execute
     * @return Attempt<T>
     */
    function attempt(callable|string|object|array $callable): Attempt
    {
        /** @var Attempt<T> */
        return Attempt::of($callable);
    }
}

if (!function_exists('Cline\Throw\raise')) {
    /**
     * Throw multiple exceptions as a group (Python-style exception groups).
     *
     * This helper allows you to collect and throw multiple exceptions at once,
     * making it ideal for validation scenarios where multiple errors can occur.
     * Inspired by Python 3.11's exception groups.
     *
     * @param array<int, Throwable> $exceptions Array of exceptions to raise
     * @param string                $message    Optional group message
     *
     * @throws ExceptionGroup When exceptions array is not empty
     *
     * @example Collect validation errors
     * ```php
     * $errors = [];
     * if (!$email) $errors[] = new RequiredFieldException('Email required');
     * if (!$password) $errors[] = new RequiredFieldException('Password required');
     *
     * raise($errors, 'Registration failed');
     * ```
     * @example Conditional raise
     * ```php
     * $errors = validateUser($data);
     * raise($errors, 'User validation failed'); // Only throws if errors exist
     * ```
     * @example Immediate raise
     * ```php
     * raise([
     *     new InvalidEmailException('Email invalid'),
     *     new WeakPasswordException('Password too weak'),
     * ], 'Validation failed');
     * ```
     */
    function raise(array $exceptions, string $message = 'Multiple exceptions occurred'): void
    {
        if ($exceptions === []) {
            return;
        }

        throw new ExceptionGroup($message, $exceptions);
    }
}

if (!function_exists('Cline\Throw\errdefer')) {
    /**
     * Create a deferred cleanup handler (Zig-style error cleanup).
     *
     * This helper creates a cleanup handler that executes callbacks only when
     * an exception occurs, inspired by Zig's errdefer. Perfect for ensuring
     * resources are cleaned up on error paths without explicit try-catch blocks.
     *
     * @example Database transaction cleanup
     * ```php
     * $cleanup = errdefer();
     * $cleanup->onError(fn() => DB::rollBack());
     *
     * DB::beginTransaction();
     * $user = User::create($data);
     * DB::commit();
     * ```
     * @example File upload cleanup
     * ```php
     * $cleanup = errdefer();
     * $tempFile = uploadToTemp($file);
     * $cleanup->onError(fn() => unlink($tempFile));
     *
     * processFile($tempFile);
     * moveToFinal($tempFile);
     * ```
     * @example Multiple cleanup steps
     * ```php
     * $cleanup = errdefer();
     * $file = fopen('data.txt', 'w');
     * $cleanup->onError(fn() => fclose($file));
     * $cleanup->onError(fn() => unlink('data.txt'));
     * ```
     */
    function errdefer(): DeferredCleanup
    {
        return new DeferredCleanup();
    }
}
