<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Support;

use Throwable;

/**
 * Go-inspired error comparison utilities.
 *
 * Provides type checking and casting for exceptions, enabling safer error
 * handling patterns. Inspired by Go's errors.Is() and errors.As() functions.
 *
 * @example Check exception type
 * ```php
 * if (Errors::is($exception, DatabaseException::class)) {
 *     // Handle database error
 * }
 * ```
 * @example Cast and use exception
 * ```php
 * $dbError = Errors::as($exception, DatabaseException::class);
 * if ($dbError !== null) {
 *     logger()->error($dbError->getQuery());
 * }
 * ```
 * @example Check wrapped exceptions
 * ```php
 * // Checks both the exception and its previous chain
 * if (Errors::is($exception, PDOException::class)) {
 *     // Will match even if PDOException is wrapped
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 *
 * @psalm-immutable
 */
final readonly class Errors
{
    /**
     * Check if an exception matches the given type.
     *
     * Examines the exception and its entire previous exception chain to
     * determine if any exception in the chain is an instance of the
     * specified class. This is useful for identifying errors that may
     * have been wrapped multiple times.
     *
     * @template T of Throwable
     *
     * @param Throwable       $exception The exception to check
     * @param class-string<T> $type      The exception class to check for
     *
     * @example Check for specific exception type
     * ```php
     * try {
     *     $user->charge($amount);
     * } catch (Throwable $e) {
     *     if (Errors::is($e, PaymentException::class)) {
     *         // Handle payment error
     *     }
     * }
     * ```
     * @example Check wrapped exceptions
     * ```php
     * try {
     *     $db->query($sql);
     * } catch (Throwable $e) {
     *     // Will match even if PDOException is wrapped in DatabaseException
     *     if (Errors::is($e, PDOException::class)) {
     *         logger()->error('Database connection failed');
     *     }
     * }
     * ```
     */
    public static function is(Throwable $exception, string $type): bool
    {
        $current = $exception;

        while ($current instanceof Throwable) {
            if ($current instanceof $type) {
                return true;
            }

            $current = $current->getPrevious();
        }

        return false;
    }

    /**
     * Cast an exception to the given type if it matches.
     *
     * Attempts to cast the exception or any exception in its previous chain
     * to the specified type. Returns the cast exception if found, or null
     * if no matching exception exists in the chain.
     *
     * @template T of Throwable
     *
     * @param  Throwable       $exception The exception to cast
     * @param  class-string<T> $type      The exception class to cast to
     * @return null|T          The cast exception, or null if no match found
     *
     * @example Cast and use exception
     * ```php
     * try {
     *     $user->charge($amount);
     * } catch (Throwable $e) {
     *     $paymentError = Errors::as($e, PaymentException::class);
     *     if ($paymentError !== null) {
     *         logger()->error('Payment failed', [
     *             'transaction_id' => $paymentError->getTransactionId(),
     *             'amount' => $paymentError->getAmount(),
     *         ]);
     *     }
     * }
     * ```
     * @example Access wrapped exception details
     * ```php
     * try {
     *     $db->query($sql);
     * } catch (Throwable $e) {
     *     $pdoError = Errors::as($e, PDOException::class);
     *     if ($pdoError !== null) {
     *         logger()->error('Query failed', [
     *             'error_code' => $pdoError->getCode(),
     *             'sql_state' => $pdoError->errorInfo[0] ?? null,
     *         ]);
     *     }
     * }
     * ```
     */
    public static function as(Throwable $exception, string $type): ?Throwable
    {
        $current = $exception;

        while ($current instanceof Throwable) {
            if ($current instanceof $type) {
                return $current;
            }

            $current = $current->getPrevious();
        }

        return null;
    }
}
