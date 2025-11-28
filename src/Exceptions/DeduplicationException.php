<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

use Cline\Throw\Concerns\ConditionallyThrowable;
use Cline\Throw\Concerns\HasErrorContext;
use Cline\Throw\Concerns\WrapsErrors;

/**
 * Exception for duplicate detection failures.
 *
 * Thrown when duplicate detection fails, deduplication processing encounters errors,
 * or duplicate operations are detected.
 *
 * @example Duplicate detected
 * ```php
 * final class DuplicateDetectedException extends DeduplicationException
 * {
 *     public static function found(string $identifier, string $entity): self
 *     {
 *         return new self("Duplicate detected for entity '{$entity}' with identifier: {$identifier}");
 *     }
 * }
 * ```
 * @example Deduplication failed
 * ```php
 * final class DeduplicationFailedException extends DeduplicationException
 * {
 *     public static function failed(string $reason): self
 *     {
 *         return new self("Deduplication processing failed: {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DeduplicationException extends IdempotencyException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
