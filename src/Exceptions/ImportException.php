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
 * Base exception for data import failures.
 *
 * Thrown when data import operations fail, import files are invalid,
 * or import processing encounters errors.
 *
 * @example Import failed
 * ```php
 * final class ImportFailedException extends ImportException
 * {
 *     public static function forFile(string $file, int $failedRows): self
 *     {
 *         return new self("Import failed for file '{$file}': {$failedRows} rows failed");
 *     }
 * }
 * ```
 * @example Invalid import format
 * ```php
 * final class InvalidImportFormatException extends ImportException
 * {
 *     public static function detected(string $file, string $expectedFormat, string $actualFormat): self
 *     {
 *         return new self("Invalid import format for '{$file}': expected {$expectedFormat}, got {$actualFormat}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ImportException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
