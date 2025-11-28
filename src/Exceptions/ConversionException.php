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
 * Base exception for data conversion errors.
 *
 * Thrown when data conversion fails, conversion between types is invalid,
 * or conversion operations encounter errors.
 *
 * @example Conversion failed
 * ```php
 * final class ConversionFailedException extends ConversionException
 * {
 *     public static function betweenTypes(string $fromType, string $toType, mixed $value): self
 *     {
 *         return new self("Conversion failed from '{$fromType}' to '{$toType}' for value: " . json_encode($value));
 *     }
 * }
 * ```
 * @example Unsupported conversion
 * ```php
 * final class UnsupportedConversionException extends ConversionException
 * {
 *     public static function detected(string $fromType, string $toType): self
 *     {
 *         return new self("Unsupported conversion from '{$fromType}' to '{$toType}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConversionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
