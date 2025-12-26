<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Concerns;

use Closure;

/**
 * Trait for transforming exception properties.
 *
 * This trait provides functional transformation methods for exception messages,
 * context, and metadata. Useful for normalizing or enhancing error information
 * as exceptions propagate through the application.
 *
 * @example Transform message
 * ```php
 * $exception->mapMessage(fn($msg) => "Payment Error: {$msg}");
 * ```
 * @example Transform context
 * ```php
 * $exception->mapContext(fn($ctx) => [...$ctx, 'transformed_at' => now()]);
 * ```
 * @example Chain transformations
 * ```php
 * $exception->mapMessage(fn($msg) => strtoupper($msg))
 *           ->mapContext(fn($ctx) => [...$ctx, 'severity' => 'critical']);
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
trait TransformsErrors
{
    /**
     * Transform the exception message.
     *
     * Applies a transformation function to the exception message, allowing you
     * to modify, prefix, or reformat the error message programmatically.
     *
     * @param Closure(string): string $callback Transformation function that receives the message and returns a new message
     *
     * @example Add prefix to message
     * ```php
     * $exception->mapMessage(fn($msg) => "API Error: {$msg}");
     * ```
     * @example Normalize message format
     * ```php
     * $exception->mapMessage(fn($msg) => ucfirst(rtrim($msg, '.')));
     * ```
     * @example Add contextual information
     * ```php
     * $exception->mapMessage(fn($msg) => "[Order {$orderId}] {$msg}");
     * ```
     */
    public function mapMessage(Closure $callback): static
    {
        $this->message = $callback($this->message);

        return $this;
    }

    /**
     * Transform the exception context.
     *
     * Applies a transformation function to the entire context array, allowing
     * you to modify, filter, or enrich the contextual information attached
     * to the exception.
     *
     * @param Closure(array<string, mixed>): array<string, mixed> $callback Transformation function
     *
     * @example Add timestamp to context
     * ```php
     * $exception->mapContext(fn($ctx) => [...$ctx, 'captured_at' => now()]);
     * ```
     * @example Filter sensitive data
     * ```php
     * $exception->mapContext(fn($ctx) => array_diff_key($ctx, ['password' => 1]));
     * ```
     * @example Transform values
     * ```php
     * $exception->mapContext(fn($ctx) => array_map('serialize', $ctx));
     * ```
     */
    public function mapContext(Closure $callback): static
    {
        $this->context = $callback($this->context);

        return $this;
    }

    /**
     * Transform the exception metadata.
     *
     * Applies a transformation function to the metadata array, allowing you
     * to modify or enrich debugging information.
     *
     * @param Closure(array<string, mixed>): array<string, mixed> $callback Transformation function
     *
     * @example Add debug information
     * ```php
     * $exception->mapMetadata(fn($meta) => [...$meta, 'memory_usage' => memory_get_usage()]);
     * ```
     * @example Filter large values
     * ```php
     * $exception->mapMetadata(fn($meta) => array_filter($meta, fn($v) => strlen(json_encode($v)) < 1000));
     * ```
     */
    public function mapMetadata(Closure $callback): static
    {
        $this->metadata = $callback($this->metadata);

        return $this;
    }

    /**
     * Transform the exception tags.
     *
     * Applies a transformation function to the tags array, allowing you
     * to normalize, filter, or enhance tags.
     *
     * @param Closure(array<int, string>): array<int, string> $callback Transformation function
     *
     * @example Normalize tags
     * ```php
     * $exception->mapTags(fn($tags) => array_map('strtolower', $tags));
     * ```
     * @example Add environment tag
     * ```php
     * $exception->mapTags(fn($tags) => [...$tags, app()->environment()]);
     * ```
     * @example Deduplicate tags
     * ```php
     * $exception->mapTags(fn($tags) => array_unique($tags));
     * ```
     */
    public function mapTags(Closure $callback): static
    {
        $this->tags = $callback($this->tags);

        return $this;
    }

    /**
     * Transform the exception notes.
     *
     * Applies a transformation function to the notes array, allowing you
     * to modify, filter, or format notes.
     *
     * @param Closure(array<int, string>): array<int, string> $callback Transformation function
     *
     * @example Add timestamps to notes
     * ```php
     * $exception->mapNotes(fn($notes) => array_map(fn($n) => "[".now()."] {$n}", $notes));
     * ```
     * @example Filter short notes
     * ```php
     * $exception->mapNotes(fn($notes) => array_filter($notes, fn($n) => strlen($n) > 10));
     * ```
     */
    public function mapNotes(Closure $callback): static
    {
        $this->notes = $callback($this->notes);

        return $this;
    }

    /**
     * Apply a comprehensive transformation to the entire exception.
     *
     * Allows transforming multiple exception properties at once by providing
     * a callback that receives the exception instance.
     *
     * @param Closure(static): void $callback Transformation function
     *
     * @example Transform multiple properties
     * ```php
     * $exception->transform(function($e) {
     *     $e->mapMessage(fn($msg) => "Critical: {$msg}")
     *       ->mapContext(fn($ctx) => [...$ctx, 'severity' => 'high'])
     *       ->addNote('Exception transformed at '.now());
     * });
     * ```
     * @example Conditional transformation
     * ```php
     * $exception->transform(function($e) {
     *     if (app()->environment('production')) {
     *         $e->mapContext(fn($ctx) => array_diff_key($ctx, ['debug' => 1]));
     *     }
     * });
     * ```
     */
    public function transform(Closure $callback): static
    {
        $callback($this);

        return $this;
    }
}
