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
 * Base exception for data corruption.
 *
 * Thrown when data corruption is detected through checksums, validation,
 * or structural integrity checks.
 *
 * @example Checksum failure
 * ```php
 * final class ChecksumFailedException extends CorruptedDataException
 * {
 *     public static function detected(string $expected, string $actual): self
 *     {
 *         return new self("Checksum mismatch: expected {$expected}, got {$actual}");
 *     }
 * }
 * ```
 * @example Invalid structure
 * ```php
 * final class StructureCorruptedException extends CorruptedDataException
 * {
 *     public static function detected(string $type): self
 *     {
 *         return new self("{$type} data structure is corrupted");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CorruptedDataException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
