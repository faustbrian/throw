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
 * Base exception for prototype cloning errors.
 *
 * Thrown when prototype cloning fails, clone operations encounter errors,
 * or prototype objects are not cloneable.
 *
 * @example Prototype cloning failed
 * ```php
 * final class PrototypeCloningException extends PrototypeException
 * {
 *     public static function failed(string $prototype, string $reason): self
 *     {
 *         return new self("Prototype '{$prototype}' cloning failed: {$reason}");
 *     }
 * }
 * ```
 * @example Object not cloneable
 * ```php
 * final class ObjectNotCloneableException extends PrototypeException
 * {
 *     public static function detected(string $class): self
 *     {
 *         return new self("Object of class '{$class}' is not cloneable");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PrototypeException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
