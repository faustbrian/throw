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
 * Exception for tenant isolation failures.
 *
 * Thrown when tenant isolation fails, isolation boundaries are violated,
 * or isolation operations encounter errors.
 *
 * @example Isolation violation
 * ```php
 * final class IsolationViolationException extends IsolationException
 * {
 *     public static function detected(string $tenant, string $resource): self
 *     {
 *         return new self("Isolation violation: tenant '{$tenant}' accessed resource outside boundary: {$resource}");
 *     }
 * }
 * ```
 * @example Isolation boundary breach
 * ```php
 * final class IsolationBoundaryBreachException extends IsolationException
 * {
 *     public static function detected(string $sourceTenant, string $targetTenant): self
 *     {
 *         return new self("Isolation boundary breach from tenant '{$sourceTenant}' to tenant: {$targetTenant}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class IsolationException extends TenantException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
