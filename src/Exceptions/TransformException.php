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
 * Base exception for data transformation errors.
 *
 * Thrown when data transformation fails (mapping, normalization,
 * conversion between formats, etc.).
 *
 * @example Transformation failed
 * ```php
 * final class DataTransformException extends TransformException
 * {
 *     public static function failed(string $from, string $to): self
 *     {
 *         return new self("Failed to transform data from {$from} to {$to}");
 *     }
 * }
 * ```
 * @example Invalid transformation
 * ```php
 * final class InvalidTransformException extends TransformException
 * {
 *     public static function unsupported(string $type): self
 *     {
 *         return new self("Unsupported transformation type: {$type}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TransformException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
