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
 * Base exception for network connection errors.
 *
 * Thrown when network connections fail, connection is refused,
 * or connection timeout occurs.
 *
 * @example Connection refused
 * ```php
 * final class ConnectionRefusedException extends ConnectionException
 * {
 *     public static function toHost(string $host, int $port): self
 *     {
 *         return new self("Connection refused to {$host}:{$port}");
 *     }
 * }
 * ```
 * @example Connection lost
 * ```php
 * final class ConnectionLostException extends ConnectionException
 * {
 *     public static function toService(string $service): self
 *     {
 *         return new self("Connection lost to service: {$service}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConnectionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
