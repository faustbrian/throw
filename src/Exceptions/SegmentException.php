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
 * Base exception for user segment errors.
 *
 * Thrown when user segment operations fail, segments are invalid,
 * or segment evaluation encounters errors.
 *
 * @example Segment not found
 * ```php
 * final class SegmentNotFoundException extends SegmentException
 * {
 *     public static function forKey(string $key): self
 *     {
 *         return new self("User segment not found: {$key}");
 *     }
 * }
 * ```
 * @example Invalid segment criteria
 * ```php
 * final class InvalidSegmentCriteriaException extends SegmentException
 * {
 *     public static function detected(string $segment, string $error): self
 *     {
 *         return new self("Invalid criteria for segment '{$segment}': {$error}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SegmentException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
