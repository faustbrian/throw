<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Concerns;

use Throwable;

use function array_filter;
use function array_values;
use function count;

/**
 * Provides methods for searching and filtering exception chains.
 *
 * Traverses exception chains via getPrevious() to find specific exception types
 * or match custom criteria. Useful for unwrapping nested exceptions to locate
 * root causes or specific error conditions buried in wrapped exception layers.
 *
 * ```php
 * // Find first validation error in chain
 * $validation = $exception->findFirst(ValidationException::class);
 *
 * // Find all database errors
 * $dbErrors = $exception->findAll(PDOException::class);
 *
 * // Custom filtering
 * $criticalErrors = $exception->filterChain(fn($e) => $e->getCode() >= 500);
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
trait FiltersExceptions
{
    /**
     * Finds the first exception matching the specified type in the chain.
     *
     * Traverses from this exception through all previous exceptions, returning
     * the first instance matching the specified type. Returns null if no match found.
     *
     * @template T of Throwable
     *
     * @param  class-string<T> $type Exception class to search for
     * @return null|T          First matching exception, or null if not found
     *
     * ```php
     * // Extract validation errors from wrapped exception
     * $validation = $e->findFirst(ValidationException::class);
     * if ($validation !== null) {
     *     return response()->json(['errors' => $validation->errors()], 422);
     * }
     *
     * // Find underlying database error
     * $pdoError = $e->findFirst(PDOException::class);
     * ```
     */
    public function findFirst(string $type): ?Throwable
    {
        $current = $this;

        while ($current instanceof Throwable) {
            if ($current instanceof $type) {
                return $current;
            }

            $current = $current->getPrevious();
        }

        return null;
    }

    /**
     * Finds all exceptions matching the specified type in the chain.
     *
     * Traverses the entire chain and collects all exceptions matching the
     * specified type, maintaining chain order from current to root cause.
     *
     * @template T of Throwable
     *
     * @param  class-string<T> $type Exception class to search for
     * @return array<int, T>   Array of all matching exceptions
     *
     * ```php
     * // Collect all validation errors from chain
     * $validationErrors = $e->findAll(ValidationException::class);
     * foreach ($validationErrors as $error) {
     *     logger()->warning($error->getMessage());
     * }
     *
     * // Count occurrences of specific error type
     * $dbErrorCount = count($e->findAll(PDOException::class));
     * ```
     */
    public function findAll(string $type): array
    {
        $matches = [];
        $current = $this;

        while ($current instanceof Throwable) {
            if ($current instanceof $type) {
                $matches[] = $current;
            }

            $current = $current->getPrevious();
        }

        return $matches;
    }

    /**
     * Returns the complete exception chain as an array.
     *
     * Collects all exceptions from this instance through the entire chain
     * to the root cause, preserving order from current to root.
     *
     * @return array<int, Throwable> Ordered array from current exception to root cause
     *
     * ```php
     * // Log complete exception chain
     * foreach ($exception->getChain() as $i => $e) {
     *     logger()->debug("#{$i}: " . get_class($e) . " - " . $e->getMessage());
     * }
     *
     * // Determine wrapping depth
     * $depth = count($exception->getChain());
     * ```
     */
    public function getChain(): array
    {
        $chain = [];
        $current = $this;

        while ($current instanceof Throwable) {
            $chain[] = $current;
            $current = $current->getPrevious();
        }

        return $chain;
    }

    /**
     * Filter the exception chain using a callback.
     *
     * Applies a filter function to each exception in the chain and returns
     * all exceptions that match the criteria.
     *
     * @param  callable(Throwable): bool $callback Filter function
     * @return array<int, Throwable>     Array of matching exceptions
     *
     * @example Filter by message content
     * ```php
     * $networkErrors = $exception->filterChain(
     *     fn($e) => str_contains($e->getMessage(), 'connection')
     * );
     * ```
     * @example Filter by code
     * ```php
     * $criticalErrors = $exception->filterChain(
     *     fn($e) => $e->getCode() >= 500
     * );
     * ```
     * @example Complex filtering
     * ```php
     * $appErrors = $exception->filterChain(function($e) {
     *     return str_starts_with(get_class($e), 'App\\Exceptions\\')
     *         && $e->getCode() > 0;
     * });
     * ```
     */
    public function filterChain(callable $callback): array
    {
        return array_values(
            array_filter(
                $this->getChain(),
                $callback,
            ),
        );
    }

    /**
     * Check if the exception chain contains a specific type.
     *
     * @param class-string<Throwable> $type Exception class to check for
     *
     * @example Check for wrapped error
     * ```php
     * if ($exception->hasInChain(PDOException::class)) {
     *     // Handle database error
     * }
     * ```
     */
    public function hasInChain(string $type): bool
    {
        return $this->findFirst($type) !== null;
    }

    /**
     * Get the root cause exception (the last in the chain).
     *
     * @example Log root cause
     * ```php
     * $root = $exception->getRootCause();
     * logger()->error('Root cause: ' . get_class($root) . ' - ' . $root->getMessage());
     * ```
     */
    public function getRootCause(): Throwable
    {
        $current = $this;

        while ($current->getPrevious() !== null) {
            $current = $current->getPrevious();
        }

        return $current;
    }

    /**
     * Get the chain depth (number of exceptions in the chain).
     *
     * @example Monitor exception wrapping
     * ```php
     * if ($exception->getChainDepth() > 5) {
     *     logger()->warning('Deep exception chain detected');
     * }
     * ```
     */
    public function getChainDepth(): int
    {
        return count($this->getChain());
    }
}
