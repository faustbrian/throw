<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

use BadMethodCallException as PhpBadMethodCallException;
use Cline\Throw\Concerns\ConditionallyThrowable;
use Cline\Throw\Concerns\HasErrorContext;
use Cline\Throw\Concerns\WrapsErrors;

/**
 * Exception thrown when a callback refers to an undefined method or if some arguments are missing.
 *
 * Thrown when attempting to call a method that doesn't exist on an object or when
 * using magic method calls incorrectly. Common in dynamic method invocations.
 *
 * @example Undefined method
 * ```php
 * final class UndefinedMethodException extends BadMethodCallException
 * {
 *     public static function onClass(string $method, string $class): self
 *     {
 *         return new self("Method '{$method}' does not exist on class '{$class}'");
 *     }
 * }
 * ```
 * @example Magic method call failure
 * ```php
 * final class InvalidMagicMethodException extends BadMethodCallException
 * {
 *     public static function forMethod(string $method): self
 *     {
 *         return new self("Magic method __call failed for method '{$method}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BadMethodCallException extends PhpBadMethodCallException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
