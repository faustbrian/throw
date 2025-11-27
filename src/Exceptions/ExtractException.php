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
 * Base exception for data extraction failures.
 *
 * Thrown when data extraction fails, source data is unavailable,
 * or extraction queries encounter errors.
 *
 * @example Extraction failed
 * ```php
 * final class ExtractionFailedException extends ExtractException
 * {
 *     public static function fromSource(string $source): self
 *     {
 *         return new self("Data extraction failed from source: {$source}");
 *     }
 * }
 * ```
 * @example Source unavailable
 * ```php
 * final class SourceUnavailableException extends ExtractException
 * {
 *     public static function detected(string $source): self
 *     {
 *         return new self("Data source unavailable: {$source}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ExtractException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
