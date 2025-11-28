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
 * Exception for timer operation failures.
 *
 * Thrown when timer operations fail, timers cannot be started or stopped,
 * or timer callbacks encounter errors.
 *
 * @example Timer operation failed
 * ```php
 * final class TimerOperationException extends TimerException
 * {
 *     public static function failed(string $operation, string $timerId): self
 *     {
 *         return new self("Timer operation '{$operation}' failed for timer: {$timerId}");
 *     }
 * }
 * ```
 * @example Timer callback error
 * ```php
 * final class TimerCallbackException extends TimerException
 * {
 *     public static function detected(string $timerId, string $error): self
 *     {
 *         return new self("Timer '{$timerId}' callback error: {$error}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TimerException extends ScheduleException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
