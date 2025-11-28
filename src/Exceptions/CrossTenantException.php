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
 * Exception for cross-tenant access errors.
 *
 * Thrown when cross-tenant access is attempted, cross-tenant operations fail,
 * or cross-tenant operations encounter errors.
 *
 * @example Cross-tenant access denied
 * ```php
 * final class CrossTenantAccessDeniedException extends CrossTenantException
 * {
 *     public static function detected(string $sourceTenant, string $targetTenant, string $resource): self
 *     {
 *         return new self("Cross-tenant access denied from '{$sourceTenant}' to '{$targetTenant}' for resource: {$resource}");
 *     }
 * }
 * ```
 * @example Cross-tenant operation not allowed
 * ```php
 * final class CrossTenantOperationException extends CrossTenantException
 * {
 *     public static function detected(string $operation, string $reason): self
 *     {
 *         return new self("Cross-tenant operation '{$operation}' not allowed: {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CrossTenantException extends IsolationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
