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
 * Base exception for CORS policy violations.
 *
 * Thrown when Cross-Origin Resource Sharing (CORS) policies are violated,
 * origins are not allowed, or preflight requests fail.
 *
 * @example Origin not allowed
 * ```php
 * final class OriginNotAllowedException extends CorsException
 * {
 *     public static function forOrigin(string $origin): self
 *     {
 *         return new self("Origin not allowed: {$origin}");
 *     }
 * }
 * ```
 * @example Method not allowed
 * ```php
 * final class MethodNotAllowedException extends CorsException
 * {
 *     public static function forMethod(string $method, string $origin): self
 *     {
 *         return new self("Method '{$method}' not allowed for origin: {$origin}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CorsException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
