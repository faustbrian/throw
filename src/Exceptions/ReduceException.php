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
 * Exception for reduce operation failures.
 *
 * Thrown when reduce operations fail, reducer functions encounter errors,
 * or reduce accumulation fails.
 *
 * @example Reduce operation failed
 * ```php
 * final class ReduceOperationException extends ReduceException
 * {
 *     public static function failed(string $reducer, string $reason): self
 *     {
 *         return new self("Reduce operation using '{$reducer}' failed: {$reason}");
 *     }
 * }
 * ```
 * @example Invalid reducer function
 * ```php
 * final class InvalidReducerException extends ReduceException
 * {
 *     public static function detected(string $reducer): self
 *     {
 *         return new self("Invalid reducer function: {$reducer}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ReduceException extends AggregationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
