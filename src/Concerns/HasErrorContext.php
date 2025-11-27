<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Concerns;

/**
 * Trait for adding contextual information to exceptions.
 *
 * This trait provides methods to attach structured context, tags, and metadata
 * to exceptions, making debugging and error tracking more effective. Context
 * data can be logged, sent to monitoring services, or displayed in error pages.
 *
 * @example Adding user context
 * ```php
 * PaymentFailedException::insufficientFunds()
 *     ->withContext(['user_id' => 123, 'amount' => 99.99])
 *     ->throwIf($balance < $amount);
 * ```
 * @example Tagging for monitoring
 * ```php
 * DatabaseException::queryFailed()
 *     ->withTags(['critical', 'database', 'payment-service'])
 *     ->throwIf($result === false);
 * ```
 * @example Attaching debug metadata
 * ```php
 * ApiException::requestFailed()
 *     ->withMetadata(['response' => $response, 'headers' => $headers])
 *     ->throwIf($statusCode >= 500);
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
trait HasErrorContext
{
    /**
     * Contextual data attached to this exception.
     *
     * @var array<string, mixed>
     */
    protected array $context = [];

    /**
     * Tags for categorizing this exception.
     *
     * @var array<int, string>
     */
    protected array $tags = [];

    /**
     * Additional metadata for debugging.
     *
     * @var array<string, mixed>
     */
    protected array $metadata = [];

    /**
     * Add contextual information to the exception.
     *
     * Context data provides structured information about the circumstances
     * under which the exception occurred. This is useful for logging,
     * monitoring, and debugging purposes.
     *
     * @param array<string, mixed> $context Associative array of context data
     *
     * @example Add request context
     * ```php
     * $exception->withContext([
     *     'user_id' => auth()->id(),
     *     'ip_address' => request()->ip(),
     *     'session_id' => session()->getId(),
     * ]);
     * ```
     * @example Add operation context
     * ```php
     * $exception->withContext([
     *     'operation' => 'charge_payment',
     *     'amount' => $amount,
     *     'currency' => 'USD',
     * ]);
     * ```
     */
    public function withContext(array $context): static
    {
        $this->context = [...$this->context, ...$context];

        return $this;
    }

    /**
     * Add tags for categorizing the exception.
     *
     * Tags help organize and filter exceptions in logging and monitoring
     * systems. They can represent severity, affected systems, error types,
     * or any other categorical information.
     *
     * @param array<int, string> $tags Array of tag strings
     *
     * @example Tag by severity and system
     * ```php
     * $exception->withTags(['critical', 'payment', 'stripe']);
     * ```
     * @example Tag by error category
     * ```php
     * $exception->withTags(['validation', 'user-input', 'profile-update']);
     * ```
     */
    public function withTags(array $tags): static
    {
        $this->tags = [...$this->tags, ...$tags];

        return $this;
    }

    /**
     * Add metadata for debugging purposes.
     *
     * Metadata contains additional debugging information that may be too
     * verbose or technical for general context. This might include full
     * request/response bodies, stack traces from external services, or
     * detailed system state.
     *
     * @param array<string, mixed> $metadata Associative array of metadata
     *
     * @example Add API response metadata
     * ```php
     * $exception->withMetadata([
     *     'request_body' => $requestBody,
     *     'response_body' => $responseBody,
     *     'response_headers' => $headers,
     *     'duration_ms' => $duration,
     * ]);
     * ```
     * @example Add database query metadata
     * ```php
     * $exception->withMetadata([
     *     'query' => $sql,
     *     'bindings' => $bindings,
     *     'execution_time' => $time,
     * ]);
     * ```
     */
    public function withMetadata(array $metadata): static
    {
        $this->metadata = [...$this->metadata, ...$metadata];

        return $this;
    }

    /**
     * Get the exception's context data.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Get the exception's tags.
     *
     * @return array<int, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Get the exception's metadata.
     *
     * @return array<string, mixed>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
