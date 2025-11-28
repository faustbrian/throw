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
 * Base exception for SSL/TLS protocol errors.
 *
 * Thrown when SSL/TLS operations fail, handshake fails,
 * or SSL/TLS configuration is invalid.
 *
 * @example Handshake failed
 * ```php
 * final class SslHandshakeException extends SslException
 * {
 *     public static function failed(string $host): self
 *     {
 *         return new self("SSL handshake failed with host: {$host}");
 *     }
 * }
 * ```
 * @example Protocol mismatch
 * ```php
 * final class SslProtocolMismatchException extends SslException
 * {
 *     public static function between(string $client, string $server): self
 *     {
 *         return new self("SSL protocol mismatch: client {$client}, server {$server}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SslException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
