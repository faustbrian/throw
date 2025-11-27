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
 * Base exception for annotation parsing errors.
 *
 * Thrown when annotation parsing fails, annotations are malformed,
 * or annotation processing encounters errors.
 *
 * @example Annotation parsing failed
 * ```php
 * final class AnnotationParsingException extends AnnotationException
 * {
 *     public static function failed(string $annotation, string $class): self
 *     {
 *         return new self("Annotation parsing failed for '{$annotation}' in class: {$class}");
 *     }
 * }
 * ```
 * @example Invalid annotation format
 * ```php
 * final class InvalidAnnotationFormatException extends AnnotationException
 * {
 *     public static function detected(string $annotation, string $reason): self
 *     {
 *         return new self("Invalid annotation format for '{$annotation}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AnnotationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
