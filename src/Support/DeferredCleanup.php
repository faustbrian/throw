<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Support;

use Closure;
use Throwable;

use function array_reverse;

/**
 * Zig-inspired deferred cleanup that runs only on error.
 *
 * DeferredCleanup allows you to register cleanup callbacks that execute only
 * when an exception is thrown, similar to Zig's errdefer. This ensures resources
 * are cleaned up on error paths without needing explicit try-catch blocks.
 *
 * Cleanup callbacks are executed in reverse order (LIFO - Last In, First Out)
 * to mirror the natural unwinding of resource allocation. This ensures that
 * resources are cleaned up in the opposite order they were created, similar
 * to how destructors work.
 *
 * The cleanup can be triggered in three ways:
 * 1. Automatically via the destructor when the object goes out of scope
 * 2. Manually by calling cleanup()
 * 3. Automatically via run() when the callback throws an exception
 *
 * @example Database transaction with cleanup
 * ```php
 * $cleanup = new DeferredCleanup();
 * $cleanup->onError(fn() => DB::rollBack());
 *
 * DB::beginTransaction();
 * $user = User::create($data); // If this throws, rollback runs
 * DB::commit();
 * ```
 * @example File upload cleanup
 * ```php
 * $cleanup = new DeferredCleanup();
 * $tempFile = $this->uploadToTemp($file);
 * $cleanup->onError(fn() => unlink($tempFile));
 *
 * $this->processFile($tempFile); // If this throws, temp file is deleted
 * $this->moveToFinal($tempFile);
 * ```
 * @example Multiple cleanup steps (LIFO order)
 * ```php
 * $cleanup = new DeferredCleanup();
 * $file = fopen('data.txt', 'w');
 * $cleanup->onError(fn() => fclose($file));    // Runs second
 * $cleanup->onError(fn() => unlink('data.txt')); // Runs first
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 *
 * @see errdefer() Global helper function for creating DeferredCleanup instances
 */
final class DeferredCleanup
{
    /**
     * Registered cleanup callbacks.
     *
     * @var array<int, Closure>
     */
    private array $callbacks = [];

    /**
     * Whether cleanup has already run.
     */
    private bool $cleaned = false;

    /**
     * Destructor ensures cleanup runs if not already done.
     *
     * This acts as a safety net to ensure cleanup callbacks execute when the
     * object goes out of scope, even if cleanup() or run() was not called.
     * However, explicit cleanup via cleanup() or run() is preferred for
     * predictable timing and better control flow.
     */
    public function __destruct()
    {
        if ($this->cleaned) {
            return;
        }

        $this->cleanup();
    }

    /**
     * Register a cleanup callback to run on error.
     *
     * Callbacks are stored and executed in reverse order (LIFO) when cleanup
     * is triggered. This method returns $this for fluent chaining.
     *
     * @param Closure(): void $callback Cleanup function to execute on error
     *
     * @return self Returns this instance for method chaining
     *
     * @example Register cleanup
     * ```php
     * $cleanup = new DeferredCleanup();
     * $cleanup->onError(fn() => fclose($handle));
     * ```
     * @example Chained registration
     * ```php
     * $cleanup = (new DeferredCleanup())
     *     ->onError(fn() => DB::rollBack())
     *     ->onError(fn() => Cache::flush());
     * ```
     */
    public function onError(Closure $callback): self
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    /**
     * Manually trigger cleanup callbacks.
     *
     * Executes all registered callbacks in reverse order (LIFO).
     * Can only run once - subsequent calls are no-ops.
     *
     * @example Manual cleanup
     * ```php
     * $cleanup = new DeferredCleanup();
     * $cleanup->onError(fn() => unlink($file));
     *
     * if ($error) {
     *     $cleanup->cleanup();
     * }
     * ```
     */
    public function cleanup(): void
    {
        if ($this->cleaned) {
            return;
        }

        $this->cleaned = true;

        // Execute in reverse order (LIFO - Last In, First Out)
        foreach (array_reverse($this->callbacks) as $callback) {
            $callback();
        }
    }

    /**
     * Run a callback with automatic cleanup on error.
     *
     * If the callback throws an exception, all registered cleanup callbacks
     * are executed in reverse order before re-throwing the exception.
     *
     * @template T
     *
     * @param Closure(): T $callback Function to execute
     *
     * @throws Throwable If the callback throws an exception
     *
     * @return T The callback's return value
     *
     * @example Auto cleanup on error
     * ```php
     * $cleanup = new DeferredCleanup();
     * $cleanup->onError(fn() => DB::rollBack());
     *
     * $result = $cleanup->run(function() {
     *     DB::beginTransaction();
     *     return $this->performWork();
     * });
     * ```
     */
    public function run(Closure $callback): mixed
    {
        try {
            return $callback();
        } catch (Throwable $throwable) {
            $this->cleanup();

            throw $throwable;
        }
    }
}
