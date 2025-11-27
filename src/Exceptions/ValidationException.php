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
 * Base exception for input validation errors.
 *
 * Validation exceptions represent errors in user input, request data, or any
 * external data that fails validation rules. These errors indicate problems
 * with data format, type, constraints, or business validation rules.
 *
 * @example Invalid email format
 * ```php
 * final class InvalidEmailException extends ValidationException
 * {
 *     public static function format(string $email): self
 *     {
 *         return new self("Invalid email format: {$email}");
 *     }
 * }
 * ```
 * @example Required field missing
 * ```php
 * final class RequiredFieldException extends ValidationException
 * {
 *     public static function missing(string $field): self
 *     {
 *         return new self("Required field missing: {$field}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ValidationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
