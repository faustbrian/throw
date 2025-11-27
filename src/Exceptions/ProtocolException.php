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
 * Base exception for protocol violation errors.
 *
 * Thrown when protocol specifications are violated, protocol negotiation fails,
 * or protocol-level operations encounter errors.
 *
 * @example Protocol violation
 * ```php
 * final class ProtocolViolationException extends ProtocolException
 * {
 *     public static function detected(string $protocol, string $violation): self
 *     {
 *         return new self("Protocol violation in {$protocol}: {$violation}");
 *     }
 * }
 * ```
 * @example Unsupported protocol
 * ```php
 * final class UnsupportedProtocolException extends ProtocolException
 * {
 *     public static function forVersion(string $protocol, string $version): self
 *     {
 *         return new self("Unsupported protocol version: {$protocol} {$version}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ProtocolException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
