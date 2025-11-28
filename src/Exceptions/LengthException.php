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
use LengthException as PhpLengthException;

/**
 * Exception thrown when a length is invalid.
 *
 * Thrown when a string, array, or collection has an invalid length - typically
 * when it's too short or too long for the required operation or constraint.
 *
 * @example String too short
 * ```php
 * final class StringTooShortException extends LengthException
 * {
 *     public static function minimum(int $actual, int $minimum): self
 *     {
 *         return new self("String length {$actual} is below minimum of {$minimum}");
 *     }
 * }
 * ```
 * @example Array size violation
 * ```php
 * final class ArraySizeException extends LengthException
 * {
 *     public static function exact(int $actual, int $expected): self
 *     {
 *         return new self("Array must contain exactly {$expected} elements, got {$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LengthException extends PhpLengthException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
