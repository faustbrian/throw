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
 * Trait for filtering exception chains.
 *
 * Provides methods to search through exception chains (via getPrevious()) and
 * find specific exception types. Useful for handling wrapped exceptions where
 * the root cause is buried in the chain.
 *
 * @example Find first validation error
 * ```php
 * $validation = $exception->findFirst(ValidationException::class);
 * if ($validation !== null) {
 *     // Handle validation error
 * }
 * ```
 * @example Find all database errors
 * ```php
 * $dbErrors = $exception->findAll(DatabaseException::class);
 * foreach ($dbErrors as $error) {
 *     logger()->error($error->getQuery());
 * }
 * ```
 * @example Get entire chain
 * ```php
 * $chain = $exception->getChain();
 * // Returns: [$exception, $previous1, $previous2, ...]
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
trait FiltersExceptions
{
    /**
     * Find the first exception of a specific type in the chain.
     *
     * Searches through the exception chain (starting with this exception)
     * and returns the first exception that matches the specified type.
     *
     * @template T of Throwable
     *
     * @param  class-string<T> $type The exception class to search for
     * @return null|T          The first matching exception, or null if not found
     *
     * @example Find first validation error
     * ```php
     * try {
     *     $service->process($data);
     * } catch (Throwable $e) {
     *     $validation = $e->findFirst(ValidationException::class);
     *     if ($validation !== null) {
     *         return response()->json(['errors' => $validation->getErrors()], 422);
     *     }
     * }
     * ```
     * @example Find wrapped PDOException
     * ```php
     * try {
     *     $db->query($sql);
     * } catch (Throwable $e) {
     *     $pdoError = $e->findFirst(PDOException::class);
     *     if ($pdoError !== null) {
     *         logger()->error('Database error', [
     *             'code' => $pdoError->getCode(),
     *         ]);
     *     }
     * }
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
     * Find all exceptions of a specific type in the chain.
     *
     * Searches through the entire exception chain and returns all exceptions
     * that match the specified type.
     *
     * @template T of Throwable
     *
     * @param  class-string<T> $type The exception class to search for
     * @return array<int, T>   Array of matching exceptions
     *
     * @example Find all validation errors
     * ```php
     * try {
     *     $service->processMultiple($items);
     * } catch (Throwable $e) {
     *     $validationErrors = $e->findAll(ValidationException::class);
     *     foreach ($validationErrors as $error) {
     *         logger()->warning($error->getMessage());
     *     }
     * }
     * ```
     * @example Count specific error types
     * ```php
     * $dbErrors = $exception->findAll(DatabaseException::class);
     * echo "Found " . count($dbErrors) . " database errors";
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
     * Get the entire exception chain as an array.
     *
     * Returns all exceptions in the chain, starting with this exception
     * and including all previous exceptions.
     *
     * @return array<int, Throwable> Array of exceptions from this to the root cause
     *
     * @example Log entire exception chain
     * ```php
     * foreach ($exception->getChain() as $i => $e) {
     *     logger()->debug("Exception #{$i}: " . get_class($e) . " - " . $e->getMessage());
     * }
     * ```
     * @example Find chain depth
     * ```php
     * $depth = count($exception->getChain());
     * echo "Exception chain depth: {$depth}";
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
