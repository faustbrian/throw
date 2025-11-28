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
use InvalidArgumentException as PhpInvalidArgumentException;

/**
 * Exception thrown when an argument is not of the expected type.
 *
 * Thrown when a function or method receives an argument of an unexpected type,
 * invalid format, or out of acceptable range of values.
 *
 * @example Type mismatch
 * ```php
 * final class InvalidTypeException extends InvalidArgumentException
 * {
 *     public static function expected(string $expected, mixed $actual): self
 *     {
 *         $type = get_debug_type($actual);
 *         return new self("Expected argument of type '{$expected}', got '{$type}'");
 *     }
 * }
 * ```
 * @example Invalid format
 * ```php
 * final class InvalidFormatException extends InvalidArgumentException
 * {
 *     public static function forEmail(string $value): self
 *     {
 *         return new self("Invalid email format: '{$value}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InvalidArgumentException extends PhpInvalidArgumentException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
