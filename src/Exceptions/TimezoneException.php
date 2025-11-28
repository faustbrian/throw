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
 * Base exception for timezone conversion errors.
 *
 * Thrown when timezone operations fail, timezone identifiers are invalid,
 * or timezone conversions encounter errors.
 *
 * @example Invalid timezone
 * ```php
 * final class InvalidTimezoneException extends TimezoneException
 * {
 *     public static function forIdentifier(string $identifier): self
 *     {
 *         return new self("Invalid timezone identifier: {$identifier}");
 *     }
 * }
 * ```
 * @example Conversion failed
 * ```php
 * final class TimezoneConversionException extends TimezoneException
 * {
 *     public static function failed(string $from, string $to): self
 *     {
 *         return new self("Failed to convert timezone from {$from} to {$to}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TimezoneException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
