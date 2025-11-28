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
 * Exception thrown when a collection is modified during iteration.
 *
 * Thrown when attempting to modify a data structure while iterating over it,
 * which can lead to undefined behavior or corrupted iteration state.
 *
 * @example Collection modified during iteration
 * ```php
 * final class CollectionModifiedException extends ConcurrentModificationException
 * {
 *     public static function duringIteration(string $collection): self
 *     {
 *         return new self("Collection '{$collection}' was modified during iteration");
 *     }
 * }
 * ```
 * @example Iterator invalidated
 * ```php
 * final class IteratorInvalidatedException extends ConcurrentModificationException
 * {
 *     public static function byModification(): self
 *     {
 *         return new self("Iterator invalidated by concurrent modification");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConcurrentModificationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
