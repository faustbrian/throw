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
 * Base exception for fallback execution errors.
 *
 * Thrown when fallback execution fails, fallback is not available,
 * or fallback operations encounter errors.
 *
 * @example Fallback execution failed
 * ```php
 * final class FallbackExecutionException extends FallbackException
 * {
 *     public static function failed(string $fallback, string $reason): self
 *     {
 *         return new self("Fallback '{$fallback}' execution failed: {$reason}");
 *     }
 * }
 * ```
 * @example Fallback not available
 * ```php
 * final class FallbackNotAvailableException extends FallbackException
 * {
 *     public static function detected(string $operation): self
 *     {
 *         return new self("Fallback not available for operation: {$operation}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FallbackException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
