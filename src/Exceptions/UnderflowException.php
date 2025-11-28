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
use UnderflowException as PhpUnderflowException;

/**
 * Exception thrown when removing an element from an empty container.
 *
 * Thrown during runtime when attempting to remove or access elements from an empty
 * data structure or when a computational underflow occurs.
 *
 * @example Empty container
 * ```php
 * final class EmptyContainerException extends UnderflowException
 * {
 *     public static function cannotPop(string $container): self
 *     {
 *         return new self("Cannot pop from empty {$container}");
 *     }
 * }
 * ```
 * @example Stack underflow
 * ```php
 * final class StackUnderflowException extends UnderflowException
 * {
 *     public static function occurred(): self
 *     {
 *         return new self("Stack underflow: cannot pop from empty stack");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class UnderflowException extends PhpUnderflowException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
