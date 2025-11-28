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
 * Exception thrown when dereferencing null.
 *
 * Thrown when attempting to access properties, methods, or perform operations
 * on a null value. Common when strict null checks are enforced.
 *
 * @example Null dereference
 * ```php
 * final class NullDereferenceException extends NullPointerException
 * {
 *     public static function onProperty(string $property): self
 *     {
 *         return new self("Cannot access property '{$property}' on null");
 *     }
 * }
 * ```
 * @example Null method call
 * ```php
 * final class NullMethodCallException extends NullPointerException
 * {
 *     public static function attempted(string $method): self
 *     {
 *         return new self("Cannot call method '{$method}' on null object");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class NullPointerException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
