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
 * Base exception for reflection operation errors.
 *
 * Thrown when reflection operations fail, classes/methods are not found,
 * or reflection analysis encounters errors.
 *
 * @example Reflection failed
 * ```php
 * final class ReflectionFailedException extends ReflectionException
 * {
 *     public static function forClass(string $class): self
 *     {
 *         return new self("Reflection failed for class: {$class}");
 *     }
 * }
 * ```
 * @example Method not found
 * ```php
 * final class MethodNotFoundException extends ReflectionException
 * {
 *     public static function detected(string $class, string $method): self
 *     {
 *         return new self("Method '{$method}' not found in class: {$class}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ReflectionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
