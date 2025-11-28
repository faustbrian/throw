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
 * Base exception for report generation failures.
 *
 * Thrown when report generation fails, report data is invalid,
 * or report rendering encounters errors.
 *
 * @example Report generation failed
 * ```php
 * final class ReportGenerationException extends ReportException
 * {
 *     public static function failed(string $report): self
 *     {
 *         return new self("Failed to generate report: {$report}");
 *     }
 * }
 * ```
 * @example Invalid report parameters
 * ```php
 * final class InvalidReportParametersException extends ReportException
 * {
 *     public static function detected(string $report, array $missing): self
 *     {
 *         $params = implode(', ', $missing);
 *         return new self("Invalid parameters for report '{$report}': missing {$params}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ReportException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
