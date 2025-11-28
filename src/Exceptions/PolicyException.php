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
 * Base exception for policy enforcement errors.
 *
 * Thrown when policy enforcement fails, policies are violated,
 * or policy operations encounter errors.
 *
 * @example Policy violation
 * ```php
 * final class PolicyViolationException extends PolicyException
 * {
 *     public static function detected(string $policy, string $user, string $action): self
 *     {
 *         return new self("Policy '{$policy}' violation: user '{$user}' not allowed to perform action: {$action}");
 *     }
 * }
 * ```
 * @example Policy evaluation failed
 * ```php
 * final class PolicyEvaluationException extends PolicyException
 * {
 *     public static function failed(string $policy, string $reason): self
 *     {
 *         return new self("Policy evaluation failed for '{$policy}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PolicyException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
