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
 * Exception thrown when end of file or stream is reached unexpectedly.
 *
 * Thrown during I/O operations when attempting to read beyond the end of a file,
 * stream, or data source when more data was expected.
 *
 * @example Unexpected end of file
 * ```php
 * final class UnexpectedEndOfFileException extends EndOfFileException
 * {
 *     public static function whileReading(string $file): self
 *     {
 *         return new self("Unexpected end of file while reading '{$file}'");
 *     }
 * }
 * ```
 * @example Stream exhausted
 * ```php
 * final class StreamExhaustedException extends EndOfFileException
 * {
 *     public static function premature(int $expected, int $actual): self
 *     {
 *         return new self("Stream ended prematurely: expected {$expected} bytes, got {$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class EndOfFileException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
