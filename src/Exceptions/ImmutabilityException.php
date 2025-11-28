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
 * Exception for immutability violation errors.
 *
 * Thrown when immutable data is modified, immutability constraints are violated,
 * or immutability operations encounter errors.
 *
 * @example Immutability violation
 * ```php
 * final class ImmutabilityViolationException extends ImmutabilityException
 * {
 *     public static function detected(string $entity, string $field): self
 *     {
 *         return new self("Immutability violation: attempt to modify immutable field '{$field}' in entity: {$entity}");
 *     }
 * }
 * ```
 * @example Immutable object modification
 * ```php
 * final class ImmutableObjectModificationException extends ImmutabilityException
 * {
 *     public static function detected(string $class): self
 *     {
 *         return new self("Immutable object modification detected for class: {$class}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ImmutabilityException extends LedgerException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
