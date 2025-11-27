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
 * Base exception for unsupported media types.
 *
 * Thrown when the request's Content-Type or format is not supported.
 * Maps to HTTP 415 Unsupported Media Type.
 *
 * @example Wrong content type
 * ```php
 * final class InvalidContentTypeException extends UnsupportedMediaTypeException
 * {
 *     public static function expected(string $expected, string $actual): self
 *     {
 *         return new self("Expected {$expected}, got {$actual}");
 *     }
 * }
 * ```
 * @example Unsupported file format
 * ```php
 * final class UnsupportedFileFormatException extends UnsupportedMediaTypeException
 * {
 *     public static function format(string $format): self
 *     {
 *         return new self("File format not supported: {$format}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class UnsupportedMediaTypeException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
