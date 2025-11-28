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
 * Exception for constraint validation failures.
 *
 * Thrown when constraint validation fails, constraints are violated,
 * or constraint operations encounter errors.
 *
 * @example Constraint violation
 * ```php
 * final class ConstraintViolationException extends ConstraintException
 * {
 *     public static function detected(string $constraint, string $field, mixed $value): self
 *     {
 *         return new self("Constraint '{$constraint}' violated for field '{$field}' with value: " . json_encode($value));
 *     }
 * }
 * ```
 * @example Unique constraint violation
 * ```php
 * final class UniqueConstraintViolationException extends ConstraintException
 * {
 *     public static function detected(string $table, string $field, mixed $value): self
 *     {
 *         return new self("Unique constraint violation in table '{$table}' for field '{$field}': value already exists");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConstraintException extends RuleException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
