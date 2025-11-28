<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

use BadFunctionCallException as PhpBadFunctionCallException;
use Cline\Throw\Concerns\ConditionallyThrowable;
use Cline\Throw\Concerns\HasErrorContext;
use Cline\Throw\Concerns\WrapsErrors;

/**
 * Exception thrown when a callback refers to an undefined function or if some arguments are missing.
 *
 * Thrown when attempting to call a function that doesn't exist or when a callback
 * is invalid. This is typically used for dynamic function calls and callbacks.
 *
 * @example Undefined function
 * ```php
 * final class UndefinedFunctionException extends BadFunctionCallException
 * {
 *     public static function named(string $function): self
 *     {
 *         return new self("Function '{$function}' is not defined");
 *     }
 * }
 * ```
 * @example Invalid callback
 * ```php
 * final class InvalidCallbackException extends BadFunctionCallException
 * {
 *     public static function notCallable(mixed $callback): self
 *     {
 *         $type = get_debug_type($callback);
 *         return new self("Value of type '{$type}' is not callable");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BadFunctionCallException extends PhpBadFunctionCallException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
