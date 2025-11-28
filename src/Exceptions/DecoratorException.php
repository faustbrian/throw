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
 * Base exception for decorator application errors.
 *
 * Thrown when decorator application fails, decorators are incompatible,
 * or decorator operations encounter errors.
 *
 * @example Decorator application failed
 * ```php
 * final class DecoratorApplicationException extends DecoratorException
 * {
 *     public static function failed(string $decorator, string $target): self
 *     {
 *         return new self("Decorator '{$decorator}' application failed for target: {$target}");
 *     }
 * }
 * ```
 * @example Incompatible decorator
 * ```php
 * final class IncompatibleDecoratorException extends DecoratorException
 * {
 *     public static function detected(string $decorator, string $target, string $reason): self
 *     {
 *         return new self("Decorator '{$decorator}' incompatible with target '{$target}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DecoratorException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
