<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Global helper functions for the Throw package.
 *
 * This file provides convenient global helper functions that wrap the core
 * functionality of the Throw package. All functions are namespaced under
 * Cline\Throw to avoid conflicts with other packages.
 *
 * Available helpers:
 * - ensure() - Create fluent assertions with conditional throwing
 * - attempt() - Execute code with Scala-style Try monad error handling
 * - raise() - Throw multiple exceptions as a Python-style exception group
 * - errdefer() - Register Zig-style deferred cleanup callbacks
 *
 * @author Brian Faust <brian@cline.sh>
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
     * assertion fails. It's the primary entry point for creating Assertion
     * instances and supports both eager (boolean) and lazy (callable) evaluation.
     *
     * When a callable is provided, it will be evaluated immediately to resolve
     * to a boolean value before creating the Assertion instance.
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
     *
     * @see Assertion For all available assertion methods
     * @param  bool|callable(): bool $condition The condition to assert (bool or callable returning bool)
     * @return Assertion             A fluent assertion builder for conditional throwing/aborting
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
     * It immediately executes the provided callable and captures either the
     * successful result or any thrown exception in an Attempt instance.
     *
     * The callable can be provided in multiple formats:
     * - Closures: fn() => doSomething()
     * - Class strings with __invoke or handle method: ProcessPayment::class
     * - Object instances with __invoke or handle method: new ProcessPayment()
     * - Array callables: [$object, 'methodName']
     *
     * @example Basic usage
     * ```php
     * $user = attempt(fn() => User::findOrFail($id))->get();
     * ```
     * @example With fallback
     * ```php
     * $config = attempt(fn() => loadConfig())->getOrElse([]);
     * ```
     * @example Convert to Option
     * ```php
     * $user = attempt(fn() => findUser($id))->toOption();
     * ```
     *
     * @see Attempt For all available methods
     * @template T
     * @param  array{class-string|object, string}|callable(): T|class-string|object $callable The code to execute
     * @return Attempt<T>                                                           An Attempt instance containing either the result or exception
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
     * making it ideal for validation scenarios where multiple errors can occur
     * simultaneously. Inspired by Python 3.11's exception groups, this enables
     * accumulating errors rather than failing fast on the first error.
     *
     * The function is a no-op if the exceptions array is empty, making it safe
     * to call unconditionally after error collection logic.
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
     *
     * @see ExceptionGroup For handling multiple exceptions
     * @param  array<int, Throwable> $exceptions Array of exceptions to raise as a group
     * @param  string                $message    Optional group message describing the error context
     * @throws ExceptionGroup        When exceptions array is not empty
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
     * Cleanup callbacks are executed in reverse order (LIFO) when triggered,
     * either automatically when the DeferredCleanup instance goes out of scope
     * during exception unwinding, or manually via cleanup() or run() methods.
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
     * @example Multiple cleanup steps (LIFO execution)
     * ```php
     * $cleanup = errdefer();
     * $file = fopen('data.txt', 'w');
     * $cleanup->onError(fn() => fclose($file));    // Runs second
     * $cleanup->onError(fn() => unlink('data.txt')); // Runs first
     * ```
     * @example With run() for scoped cleanup
     * ```php
     * $cleanup = errdefer();
     * $cleanup->onError(fn() => DB::rollBack());
     *
     * $result = $cleanup->run(function() {
     *     DB::beginTransaction();
     *     return performWork();
     * });
     * ```
     *
     * @see DeferredCleanup For all available cleanup methods
     * @return DeferredCleanup A new cleanup handler for registering error callbacks
     */
    function errdefer(): DeferredCleanup
    {
        return new DeferredCleanup();
    }
}
