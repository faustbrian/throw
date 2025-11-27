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
 * Base exception for stream operation failures.
 *
 * Thrown when stream operations fail, streams cannot be opened,
 * or stream read/write encounters errors.
 *
 * @example Stream not readable
 * ```php
 * final class StreamNotReadableException extends StreamException
 * {
 *     public static function forStream(string $stream): self
 *     {
 *         return new self("Stream is not readable: {$stream}");
 *     }
 * }
 * ```
 * @example Stream closed
 * ```php
 * final class StreamClosedException extends StreamException
 * {
 *     public static function cannotOperate(): self
 *     {
 *         return new self('Cannot operate on closed stream');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class StreamException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
