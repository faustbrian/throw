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
 * Base exception for checksum verification failures.
 *
 * Thrown when checksum verification fails, checksums don't match,
 * or checksum calculation encounters errors.
 *
 * @example Checksum mismatch
 * ```php
 * final class ChecksumMismatchException extends ChecksumException
 * {
 *     public static function forFile(string $file, string $expected, string $actual): self
 *     {
 *         return new self("Checksum mismatch for '{$file}': expected {$expected}, got {$actual}");
 *     }
 * }
 * ```
 * @example Invalid checksum
 * ```php
 * final class InvalidChecksumException extends ChecksumException
 * {
 *     public static function format(string $checksum): self
 *     {
 *         return new self("Invalid checksum format: {$checksum}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ChecksumException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
