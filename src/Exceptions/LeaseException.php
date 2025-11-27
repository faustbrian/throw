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
 * Exception for resource lease errors.
 *
 * Thrown when resource leasing fails, leases expire,
 * or lease operations encounter errors.
 *
 * @example Lease acquisition failed
 * ```php
 * final class LeaseAcquisitionException extends LeaseException
 * {
 *     public static function failed(string $resource, string $reason): self
 *     {
 *         return new self("Lease acquisition failed for resource '{$resource}': {$reason}");
 *     }
 * }
 * ```
 * @example Lease expired
 * ```php
 * final class LeaseExpiredException extends LeaseException
 * {
 *     public static function detected(string $leaseId, string $resource): self
 *     {
 *         return new self("Lease '{$leaseId}' expired for resource: {$resource}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LeaseException extends ResourceException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
