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
 * Exception for metadata extraction errors.
 *
 * Thrown when metadata extraction fails, metadata is malformed,
 * or metadata operations encounter errors.
 *
 * @example Metadata extraction failed
 * ```php
 * final class MetadataExtractionException extends MetadataException
 * {
 *     public static function failed(string $class, string $reason): self
 *     {
 *         return new self("Metadata extraction failed for class '{$class}': {$reason}");
 *     }
 * }
 * ```
 * @example Invalid metadata format
 * ```php
 * final class InvalidMetadataFormatException extends MetadataException
 * {
 *     public static function detected(string $class, string $key): self
 *     {
 *         return new self("Invalid metadata format for key '{$key}' in class: {$class}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MetadataException extends ReflectionException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
