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
 * Base exception for rollforward operation errors.
 *
 * Thrown when rollforward operations fail, forward recovery encounters errors,
 * or rollforward state is inconsistent.
 *
 * @example Rollforward failed
 * ```php
 * final class RollforwardFailedException extends RollforwardException
 * {
 *     public static function forOperation(string $operation, string $reason): self
 *     {
 *         return new self("Rollforward operation '{$operation}' failed: {$reason}");
 *     }
 * }
 * ```
 * @example Inconsistent rollforward state
 * ```php
 * final class InconsistentRollforwardStateException extends RollforwardException
 * {
 *     public static function detected(string $saga, string $expectedState, string $actualState): self
 *     {
 *         return new self("Inconsistent rollforward state for saga '{$saga}': expected={$expectedState}, actual={$actualState}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RollforwardException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
