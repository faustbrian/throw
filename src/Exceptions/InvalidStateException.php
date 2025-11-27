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
 * Base exception for invalid state errors.
 *
 * Thrown when an object or resource is in an invalid state for the
 * requested operation. Clearer than LogicException for state issues.
 *
 * @example Invalid order state
 * ```php
 * final class OrderInvalidStateException extends InvalidStateException
 * {
 *     public static function cannotShip(string $status): self
 *     {
 *         return new self("Cannot ship order in {$status} state");
 *     }
 * }
 * ```
 * @example State transition error
 * ```php
 * final class InvalidTransitionException extends InvalidStateException
 * {
 *     public static function from(string $from, string $to): self
 *     {
 *         return new self("Cannot transition from {$from} to {$to}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InvalidStateException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
