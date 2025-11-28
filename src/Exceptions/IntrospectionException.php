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
 * Exception for object introspection failures.
 *
 * Thrown when object introspection fails, properties are inaccessible,
 * or introspection operations encounter errors.
 *
 * @example Introspection failed
 * ```php
 * final class IntrospectionFailedException extends IntrospectionException
 * {
 *     public static function forObject(string $class, string $reason): self
 *     {
 *         return new self("Introspection failed for object '{$class}': {$reason}");
 *     }
 * }
 * ```
 * @example Property not accessible
 * ```php
 * final class PropertyNotAccessibleException extends IntrospectionException
 * {
 *     public static function detected(string $class, string $property): self
 *     {
 *         return new self("Property '{$property}' not accessible in class: {$class}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class IntrospectionException extends ReflectionException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
