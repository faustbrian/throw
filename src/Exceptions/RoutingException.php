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
 * Base exception for routing failures.
 *
 * Thrown when route resolution fails, routes are not found,
 * or routing configuration is invalid.
 *
 * @example Route not found
 * ```php
 * final class RouteNotFoundException extends RoutingException
 * {
 *     public static function forUri(string $uri): self
 *     {
 *         return new self("No route found for URI: {$uri}");
 *     }
 * }
 * ```
 * @example Invalid route parameter
 * ```php
 * final class InvalidRouteParameterException extends RoutingException
 * {
 *     public static function failed(string $param, string $constraint): self
 *     {
 *         return new self("Parameter '{$param}' does not match constraint: {$constraint}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RoutingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
