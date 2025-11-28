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
 * Exception thrown when maximum recursion depth is exceeded.
 *
 * Thrown when a recursive function or algorithm exceeds the maximum allowed
 * recursion depth, potentially causing stack overflow or infinite recursion.
 *
 * @example Max depth exceeded
 * ```php
 * final class MaxRecursionDepthException extends RecursionException
 * {
 *     public static function exceeded(int $maxDepth, string $function): self
 *     {
 *         return new self("Maximum recursion depth of {$maxDepth} exceeded in {$function}");
 *     }
 * }
 * ```
 * @example Infinite recursion detected
 * ```php
 * final class InfiniteRecursionException extends RecursionException
 * {
 *     public static function detected(string $pattern): self
 *     {
 *         return new self("Infinite recursion detected: {$pattern}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RecursionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
