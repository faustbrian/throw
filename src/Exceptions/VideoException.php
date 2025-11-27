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
 * Base exception for video processing errors.
 *
 * Thrown when video operations fail (encode, transcode, thumbnail
 * generation, etc.) or when video processing libraries encounter errors.
 *
 * @example Video encoding failed
 * ```php
 * final class VideoEncodingException extends VideoException
 * {
 *     public static function failed(string $codec): self
 *     {
 *         return new self("Failed to encode video with codec: {$codec}");
 *     }
 * }
 * ```
 * @example Thumbnail generation failed
 * ```php
 * final class VideoThumbnailException extends VideoException
 * {
 *     public static function failed(int $timestamp): self
 *     {
 *         return new self("Failed to generate thumbnail at {$timestamp}s");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class VideoException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
