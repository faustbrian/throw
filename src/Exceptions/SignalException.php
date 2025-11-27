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
 * Base exception for signal handling failures.
 *
 * Thrown when signal handling fails, signals cannot be registered,
 * or signal processing encounters errors.
 *
 * @example Signal handler failed
 * ```php
 * final class SignalHandlerException extends SignalException
 * {
 *     public static function forSignal(int $signal): self
 *     {
 *         return new self("Failed to register handler for signal: {$signal}");
 *     }
 * }
 * ```
 * @example Signal received
 * ```php
 * final class SignalReceivedException extends SignalException
 * {
 *     public static function terminate(int $signal): self
 *     {
 *         return new self("Received termination signal: {$signal}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SignalException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
