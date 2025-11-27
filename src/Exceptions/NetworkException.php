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
 * Base exception for network-related errors.
 *
 * Thrown when network operations fail (DNS, connection, socket errors, etc.).
 * Extends InfrastructureException for network-specific failures.
 *
 * @example Connection refused
 * ```php
 * final class ConnectionRefusedException extends NetworkException
 * {
 *     public static function toHost(string $host, int $port): self
 *     {
 *         return new self("Connection refused to {$host}:{$port}");
 *     }
 * }
 * ```
 * @example DNS resolution failure
 * ```php
 * final class DnsException extends NetworkException
 * {
 *     public static function cannotResolve(string $hostname): self
 *     {
 *         return new self("Cannot resolve hostname: {$hostname}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class NetworkException extends InfrastructureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
