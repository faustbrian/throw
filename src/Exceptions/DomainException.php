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
use DomainException as PhpDomainException;

/**
 * Exception thrown when a value does not adhere to a defined valid data domain.
 *
 * Thrown when a value is outside the valid domain of acceptable values. This represents
 * violations of domain-specific business rules or data integrity constraints.
 *
 * @example Invalid status
 * ```php
 * final class InvalidStatusException extends DomainException
 * {
 *     public static function transition(string $from, string $to): self
 *     {
 *         return new self("Cannot transition from status '{$from}' to '{$to}'");
 *     }
 * }
 * ```
 * @example Business rule violation
 * ```php
 * final class BusinessRuleViolationException extends DomainException
 * {
 *     public static function forRule(string $rule, string $reason): self
 *     {
 *         return new self("Business rule '{$rule}' violated: {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DomainException extends PhpDomainException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
