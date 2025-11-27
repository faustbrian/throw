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
 * Base exception for interceptor execution errors.
 *
 * Thrown when interceptor execution fails, interceptor chains break,
 * or interceptor operations encounter errors.
 *
 * @example Interceptor execution failed
 * ```php
 * final class InterceptorExecutionException extends InterceptorException
 * {
 *     public static function failed(string $interceptor, string $method): self
 *     {
 *         return new self("Interceptor '{$interceptor}' execution failed for method: {$method}");
 *     }
 * }
 * ```
 * @example Interceptor chain broken
 * ```php
 * final class InterceptorChainException extends InterceptorException
 * {
 *     public static function detected(string $interceptor, string $reason): self
 *     {
 *         return new self("Interceptor chain broken at '{$interceptor}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InterceptorException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
