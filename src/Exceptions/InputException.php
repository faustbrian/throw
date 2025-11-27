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
 * Exception for console input validation errors.
 *
 * Thrown when console input is invalid, input reading fails,
 * or interactive input encounters errors.
 *
 * @example Invalid input
 * ```php
 * final class InvalidInputException extends InputException
 * {
 *     public static function expected(string $expected, string $received): self
 *     {
 *         return new self("Expected {$expected} input, received: {$received}");
 *     }
 * }
 * ```
 * @example Input required
 * ```php
 * final class InputRequiredException extends InputException
 * {
 *     public static function forField(string $field): self
 *     {
 *         return new self("Input required for field: {$field}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InputException extends ConsoleException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
