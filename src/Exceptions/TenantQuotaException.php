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
 * Base exception for tenant quota violations.
 *
 * Thrown when tenants exceed their allocated quotas for users, storage,
 * API calls, or other resources.
 *
 * @example Storage quota exceeded
 * ```php
 * final class StorageQuotaException extends TenantQuotaException
 * {
 *     public static function exceeded(int $used, int $limit): self
 *     {
 *         return new self("Storage quota exceeded: {$used}/{$limit} GB");
 *     }
 * }
 * ```
 * @example User limit reached
 * ```php
 * final class UserLimitException extends TenantQuotaException
 * {
 *     public static function reached(int $limit): self
 *     {
 *         return new self("Maximum {$limit} users reached for this tenant");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TenantQuotaException extends TenantException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
