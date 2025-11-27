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
 * Base exception for code instrumentation errors.
 *
 * Thrown when code instrumentation fails, instrumentation hooks fail,
 * or instrumentation configuration is invalid.
 *
 * @example Instrumentation failed
 * ```php
 * final class InstrumentationFailedException extends InstrumentationException
 * {
 *     public static function forClass(string $class): self
 *     {
 *         return new self("Failed to instrument class: {$class}");
 *     }
 * }
 * ```
 * @example Hook registration failed
 * ```php
 * final class HookRegistrationException extends InstrumentationException
 * {
 *     public static function failed(string $hook): self
 *     {
 *         return new self("Failed to register instrumentation hook: {$hook}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InstrumentationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
