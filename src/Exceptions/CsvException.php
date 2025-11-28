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
 * Base exception for CSV parsing failures.
 *
 * Thrown when CSV parsing fails, CSV format is invalid,
 * or CSV column mapping encounters errors.
 *
 * @example Invalid format
 * ```php
 * final class InvalidCsvFormatException extends CsvException
 * {
 *     public static function atRow(int $row, string $error): self
 *     {
 *         return new self("Invalid CSV format at row {$row}: {$error}");
 *     }
 * }
 * ```
 * @example Column mismatch
 * ```php
 * final class CsvColumnMismatchException extends CsvException
 * {
 *     public static function expected(int $expected, int $actual, int $row): self
 *     {
 *         return new self("CSV column mismatch at row {$row}: expected {$expected}, got {$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CsvException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
