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
 * Exception for HTTP protocol violations.
 *
 * Thrown when HTTP protocol specifications are violated,
 * HTTP headers are malformed, or HTTP version is unsupported.
 *
 * @example Malformed headers
 * ```php
 * final class MalformedHttpHeadersException extends HttpProtocolException
 * {
 *     public static function detected(string $header): self
 *     {
 *         return new self("Malformed HTTP header: {$header}");
 *     }
 * }
 * ```
 * @example Invalid method
 * ```php
 * final class InvalidHttpMethodException extends HttpProtocolException
 * {
 *     public static function forMethod(string $method): self
 *     {
 *         return new self("Invalid HTTP method: {$method}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class HttpProtocolException extends ProtocolException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
