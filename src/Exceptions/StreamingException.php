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
 * Base exception for real-time streaming errors.
 *
 * Thrown when streaming operations fail, stream connections drop,
 * or real-time data processing encounters errors.
 *
 * @example Stream connection failed
 * ```php
 * final class StreamConnectionException extends StreamingException
 * {
 *     public static function failed(string $stream): self
 *     {
 *         return new self("Failed to connect to stream: {$stream}");
 *     }
 * }
 * ```
 * @example Stream processing error
 * ```php
 * final class StreamProcessingException extends StreamingException
 * {
 *     public static function failed(string $stream, string $reason): self
 *     {
 *         return new self("Stream processing failed for '{$stream}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class StreamingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
