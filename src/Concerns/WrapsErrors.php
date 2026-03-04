<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Concerns;

use Cline\Throw\Exceptions\ExceptionGroup;
use Throwable;

/**
 * Trait for wrapping underlying exceptions.
 *
 * This trait provides the ability to wrap a lower-level exception (such as a
 * PDOException or HTTP client exception) with a domain-specific exception while
 * preserving the original error for debugging. This follows the Go error wrapping
 * pattern and helps maintain clean error boundaries between layers.
 *
 * @example Wrap database exception
 * ```php
 * try {
 *     $db->query($sql);
 * } catch (PDOException $e) {
 *     throw DatabaseException::queryFailed()->wrap($e);
 * }
 * ```
 * @example Wrap API exception
 * ```php
 * try {
 *     $client->request('GET', '/users');
 * } catch (RequestException $e) {
 *     throw ApiException::requestFailed()->wrap($e);
 * }
 * ```
 * @example Access wrapped exception
 * ```php
 * $wrapped = $exception->getWrapped();
 * if ($wrapped instanceof PDOException) {
 *     // Handle database-specific error
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
trait WrapsErrors
{
    /**
     * The underlying exception that was wrapped.
     */
    protected ?Throwable $wrapped = null;

    /**
     * Wrap an underlying exception while preserving it for debugging.
     *
     * This method allows you to catch low-level exceptions (like PDOException)
     * and re-throw them as domain-specific exceptions while maintaining access
     * to the original exception. The wrapped exception becomes the "previous"
     * exception in the exception chain.
     *
     * @param Throwable $exception The exception to wrap
     *
     * @example Wrap database exception with context
     * ```php
     * try {
     *     $db->query($sql);
     * } catch (PDOException $e) {
     *     throw DatabaseException::queryFailed()
     *         ->wrap($e)
     *         ->withContext(['query' => $sql]);
     * }
     * ```
     * @example Wrap and chain multiple exceptions
     * ```php
     * try {
     *     $this->paymentGateway->charge($amount);
     * } catch (GatewayException $e) {
     *     throw PaymentFailedException::gatewayError()
     *         ->wrap($e)
     *         ->withTags(['payment', 'critical']);
     * }
     * ```
     */
    public function wrap(Throwable $exception): static
    {
        $this->wrapped = $exception;

        // Handle ExceptionGroup which has different constructor signature
        if ($this instanceof ExceptionGroup) {
            // @phpstan-ignore-next-line new.static, arguments.count, argument.type
            $new = new static($this->getMessage(), $this->getExceptions(), $this->getCode(), $exception);
        } else {
            // @phpstan-ignore-next-line new.static, arguments.count, argument.type
            $new = new static($this->getMessage(), $this->getCode(), $exception);
        }

        // Copy any context, tags, or metadata from the current instance
        $new->withContext($this->getContext());
        $new->withTags($this->getTags());
        $new->withMetadata($this->getMetadata());

        $new->wrapped = $exception;

        return $new;
    }

    /**
     * Get the wrapped exception.
     *
     * Returns the underlying exception that was wrapped, or null if no
     * exception was wrapped.
     *
     * @example Check wrapped exception type
     * ```php
     * if ($exception->getWrapped() instanceof PDOException) {
     *     // Database-specific handling
     * }
     * ```
     */
    public function getWrapped(): ?Throwable
    {
        return $this->wrapped;
    }

    /**
     * Check if this exception wraps another exception.
     */
    public function hasWrapped(): bool
    {
        return $this->wrapped !== null;
    }
}
