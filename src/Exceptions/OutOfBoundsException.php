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
use OutOfBoundsException as PhpOutOfBoundsException;

/**
 * Exception thrown when a value is not a valid key.
 *
 * Thrown when attempting to access a collection, array, or object with an invalid key
 * that doesn't exist in the data structure. This is a runtime logic error.
 *
 * @example Invalid array key
 * ```php
 * final class InvalidKeyException extends OutOfBoundsException
 * {
 *     public static function notFound(string|int $key): self
 *     {
 *         return new self("Key '{$key}' does not exist");
 *     }
 * }
 * ```
 * @example Missing property
 * ```php
 * final class PropertyNotFoundException extends OutOfBoundsException
 * {
 *     public static function onObject(string $property, string $class): self
 *     {
 *         return new self("Property '{$property}' not found on {$class}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class OutOfBoundsException extends PhpOutOfBoundsException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
