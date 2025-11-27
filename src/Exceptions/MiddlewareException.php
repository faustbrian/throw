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
 * Base exception for middleware execution errors.
 *
 * Thrown when middleware execution fails, middleware is not found,
 * or middleware chain processing encounters errors.
 *
 * @example Middleware not found
 * ```php
 * final class MiddlewareNotFoundException extends MiddlewareException
 * {
 *     public static function forAlias(string $alias): self
 *     {
 *         return new self("Middleware not found: {$alias}");
 *     }
 * }
 * ```
 * @example Middleware execution failed
 * ```php
 * final class MiddlewareExecutionException extends MiddlewareException
 * {
 *     public static function failed(string $middleware): self
 *     {
 *         return new self("Middleware execution failed: {$middleware}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MiddlewareException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
