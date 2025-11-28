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
 * Exception thrown when an operation is interrupted.
 *
 * Thrown when a long-running operation is interrupted by an external signal,
 * user action, or system event. Common in concurrent or async operations.
 *
 * @example Operation cancelled
 * ```php
 * final class OperationCancelledException extends InterruptedException
 * {
 *     public static function byUser(string $operation): self
 *     {
 *         return new self("Operation '{$operation}' was cancelled by user");
 *     }
 * }
 * ```
 * @example Signal received
 * ```php
 * final class SignalInterruptException extends InterruptedException
 * {
 *     public static function received(int $signal): self
 *     {
 *         return new self("Process interrupted by signal {$signal}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InterruptedException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
