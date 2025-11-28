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
use UnexpectedValueException as PhpUnexpectedValueException;

/**
 * Exception thrown when a value does not match with a set of values.
 *
 * Thrown during runtime when encountering a value that is technically valid but
 * semantically unexpected or doesn't match the required set of acceptable values.
 *
 * @example Invalid enum value
 * ```php
 * final class InvalidEnumValueException extends UnexpectedValueException
 * {
 *     public static function notAllowed(mixed $value, array $allowed): self
 *     {
 *         $list = implode(', ', $allowed);
 *         return new self("Value '{$value}' is not allowed. Expected one of: {$list}");
 *     }
 * }
 * ```
 * @example Unexpected return type
 * ```php
 * final class UnexpectedReturnException extends UnexpectedValueException
 * {
 *     public static function fromCallable(string $expected, mixed $actual): self
 *     {
 *         $type = get_debug_type($actual);
 *         return new self("Expected return type '{$expected}', got '{$type}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class UnexpectedValueException extends PhpUnexpectedValueException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
