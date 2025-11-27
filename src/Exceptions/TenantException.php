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
 * Base exception for multi-tenant operation errors.
 *
 * Thrown when multi-tenant operations fail, tenant identification fails,
 * or tenant operations encounter errors.
 *
 * @example Tenant operation failed
 * ```php
 * final class TenantOperationException extends TenantException
 * {
 *     public static function failed(string $operation, string $tenant): self
 *     {
 *         return new self("Tenant operation '{$operation}' failed for tenant: {$tenant}");
 *     }
 * }
 * ```
 * @example Tenant not found
 * ```php
 * final class TenantNotFoundException extends TenantException
 * {
 *     public static function detected(string $tenantId): self
 *     {
 *         return new self("Tenant not found: {$tenantId}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TenantException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
