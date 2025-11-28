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
 * Exception for event listener errors.
 *
 * Thrown when event listeners fail, listener registration encounters errors,
 * or listener execution fails.
 *
 * @example Listener execution failed
 * ```php
 * final class ListenerExecutionException extends ListenerException
 * {
 *     public static function failed(string $listener, string $event): self
 *     {
 *         return new self("Listener '{$listener}' execution failed for event: {$event}");
 *     }
 * }
 * ```
 * @example Listener not found
 * ```php
 * final class ListenerNotFoundException extends ListenerException
 * {
 *     public static function detected(string $listener): self
 *     {
 *         return new self("Event listener not found: {$listener}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ListenerException extends EventException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
