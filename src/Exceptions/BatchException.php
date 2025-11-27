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
use RuntimeException;

/**
 * Base exception for batch operation failures.
 *
 * Thrown when batch processing fails, batch size limits are exceeded,
 * or batch operations encounter errors.
 *
 * @example Batch failed
 * ```php
 * final class BatchFailedException extends BatchException
 * {
 *     public static function forOperation(string $operation, int $failedCount): self
 *     {
 *         return new self("Batch operation '{$operation}' failed with {$failedCount} failures");
 *     }
 * }
 * ```
 * @example Batch size exceeded
 * ```php
 * final class BatchSizeExceededException extends BatchException
 * {
 *     public static function detected(int $size, int $maxSize): self
 *     {
 *         return new self("Batch size {$size} exceeded maximum allowed: {$maxSize}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BatchException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
