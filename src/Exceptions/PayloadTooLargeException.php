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
 * Base exception for request payload size violations.
 *
 * Thrown when HTTP request payloads exceed size limits.
 * Maps to HTTP 413 Payload Too Large.
 *
 * @example File too large
 * ```php
 * final class FileTooLargeException extends PayloadTooLargeException
 * {
 *     public static function exceeds(int $size, int $limit): self
 *     {
 *         return new self("File size {$size} bytes exceeds limit of {$limit} bytes");
 *     }
 * }
 * ```
 * @example Request body too large
 * ```php
 * final class RequestBodyTooLargeException extends PayloadTooLargeException
 * {
 *     public static function exceeds(int $limit): self
 *     {
 *         return new self("Request body exceeds {$limit} bytes");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PayloadTooLargeException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
