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
 * Exception for business rule violations.
 *
 * Thrown when business rules are violated, rule validation fails,
 * or rule operations encounter errors.
 *
 * @example Business rule violation
 * ```php
 * final class BusinessRuleViolationException extends RuleException
 * {
 *     public static function detected(string $rule, string $entity, string $reason): self
 *     {
 *         return new self("Business rule '{$rule}' violated for entity '{$entity}': {$reason}");
 *     }
 * }
 * ```
 * @example Rule validation failed
 * ```php
 * final class RuleValidationException extends RuleException
 * {
 *     public static function failed(string $rule, array $errors): self
 *     {
 *         return new self("Rule validation failed for '{$rule}': " . json_encode($errors));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RuleException extends PolicyException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
