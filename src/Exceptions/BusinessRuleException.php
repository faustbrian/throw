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
 * Base exception for business rule violations.
 *
 * Alternative to DomainException with clearer naming for business logic
 * violations. Use when domain rules are broken.
 *
 * @example Age requirement
 * ```php
 * final class AgeRequirementException extends BusinessRuleException
 * {
 *     public static function minimumAge(int $minimum): self
 *     {
 *         return new self("Must be at least {$minimum} years old");
 *     }
 * }
 * ```
 * @example Purchase limit
 * ```php
 * final class PurchaseLimitException extends BusinessRuleException
 * {
 *     public static function exceeded(int $limit): self
 *     {
 *         return new self("Purchase limit of {$limit} items exceeded");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BusinessRuleException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
