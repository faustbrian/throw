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
 * Exception thrown when writing to a closed stream or socket.
 *
 * Thrown when attempting to write data to a stream, socket, or pipe that has
 * been closed on the receiving end. Common in network and IPC scenarios.
 *
 * @example Socket closed
 * ```php
 * final class SocketClosedException extends BrokenPipeException
 * {
 *     public static function whileWriting(string $address): self
 *     {
 *         return new self("Socket closed while writing to '{$address}'");
 *     }
 * }
 * ```
 * @example Pipe broken
 * ```php
 * final class PipeBrokenException extends BrokenPipeException
 * {
 *     public static function receiverClosed(): self
 *     {
 *         return new self("Pipe broken: receiver has closed the connection");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BrokenPipeException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
