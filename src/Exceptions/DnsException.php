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
 * Base exception for DNS resolution failures.
 *
 * Thrown when DNS lookups fail, hostnames cannot be resolved,
 * or DNS queries encounter errors.
 *
 * @example Host not found
 * ```php
 * final class HostNotFoundException extends DnsException
 * {
 *     public static function forHostname(string $hostname): self
 *     {
 *         return new self("DNS lookup failed for hostname: {$hostname}");
 *     }
 * }
 * ```
 * @example DNS timeout
 * ```php
 * final class DnsTimeoutException extends DnsException
 * {
 *     public static function forQuery(string $hostname): self
 *     {
 *         return new self("DNS query timed out for: {$hostname}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DnsException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
