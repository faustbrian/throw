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
 * Base exception for invalid data formats.
 *
 * Thrown when data doesn't match expected formats (dates, UUIDs,
 * phone numbers, etc.). More specific than ValidationException.
 *
 * @example Invalid date format
 * ```php
 * final class InvalidDateFormatException extends FormatException
 * {
 *     public static function expected(string $format): self
 *     {
 *         return new self("Date must be in {$format} format");
 *     }
 * }
 * ```
 * @example Invalid UUID
 * ```php
 * final class InvalidUuidException extends FormatException
 * {
 *     public static function malformed(string $value): self
 *     {
 *         return new self("Invalid UUID format: {$value}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FormatException extends ValidationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
