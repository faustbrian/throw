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
 * Exception thrown when unwrapping None/Nothing in Option/Maybe patterns.
 *
 * Thrown when attempting to unwrap or access a value from an Option/Maybe type
 * that contains None/Nothing. Common in functional programming patterns.
 *
 * @example Unwrap None
 * ```php
 * final class UnwrapNoneException extends NoneException
 * {
 *     public static function attempted(): self
 *     {
 *         return new self("Cannot unwrap None value");
 *     }
 * }
 * ```
 * @example Empty Option
 * ```php
 * final class EmptyOptionException extends NoneException
 * {
 *     public static function forOperation(string $operation): self
 *     {
 *         return new self("Cannot perform '{$operation}' on empty Option");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class NoneException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
