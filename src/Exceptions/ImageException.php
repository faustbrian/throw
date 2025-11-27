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
 * Base exception for image processing errors.
 *
 * Thrown when image operations fail (resize, crop, convert, optimize, etc.)
 * or when image libraries encounter errors.
 *
 * @example Image resize failed
 * ```php
 * final class ImageResizeException extends ImageException
 * {
 *     public static function failed(int $width, int $height): self
 *     {
 *         return new self("Failed to resize image to {$width}x{$height}");
 *     }
 * }
 * ```
 * @example Invalid image format
 * ```php
 * final class InvalidImageFormatException extends ImageException
 * {
 *     public static function unsupported(string $format): self
 *     {
 *         return new self("Unsupported image format: {$format}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ImageException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
