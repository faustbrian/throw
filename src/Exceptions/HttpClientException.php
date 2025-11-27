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
 * Base exception for HTTP client request failures.
 *
 * Thrown when HTTP client requests fail, responses are invalid,
 * or network communication encounters errors.
 *
 * @example Request failed
 * ```php
 * final class HttpRequestFailedException extends HttpClientException
 * {
 *     public static function forUrl(string $url, int $statusCode): self
 *     {
 *         return new self("HTTP request to '{$url}' failed with status: {$statusCode}");
 *     }
 * }
 * ```
 * @example Request timeout
 * ```php
 * final class HttpRequestTimeoutException extends HttpClientException
 * {
 *     public static function after(string $url, int $timeout): self
 *     {
 *         return new self("HTTP request to '{$url}' timed out after {$timeout} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class HttpClientException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
