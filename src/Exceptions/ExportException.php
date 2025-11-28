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
 * Base exception for data export errors.
 *
 * Thrown when data export operations fail, export formatting encounters errors,
 * or export file generation fails.
 *
 * @example Export failed
 * ```php
 * final class ExportFailedException extends ExportException
 * {
 *     public static function forData(string $format, int $rowCount): self
 *     {
 *         return new self("Export failed for {$rowCount} rows to format: {$format}");
 *     }
 * }
 * ```
 * @example Export format not supported
 * ```php
 * final class UnsupportedExportFormatException extends ExportException
 * {
 *     public static function detected(string $format, array $supportedFormats): self
 *     {
 *         return new self("Unsupported export format '{$format}': supported formats are " . implode(', ', $supportedFormats));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ExportException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
