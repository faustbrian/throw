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
 * Base exception for event dispatching errors.
 *
 * Thrown when event dispatching fails, event handlers encounter errors,
 * or event operations fail.
 *
 * @example Event dispatch failed
 * ```php
 * final class EventDispatchException extends EventException
 * {
 *     public static function failed(string $event): self
 *     {
 *         return new self("Event dispatch failed: {$event}");
 *     }
 * }
 * ```
 * @example Event not found
 * ```php
 * final class EventNotFoundException extends EventException
 * {
 *     public static function detected(string $event): self
 *     {
 *         return new self("Event not found: {$event}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class EventException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
