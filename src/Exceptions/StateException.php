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
 * Base exception for state management issues.
 *
 * Thrown when application state is invalid, state transitions fail,
 * or state synchronization encounters errors.
 *
 * @example Invalid state
 * ```php
 * final class InvalidStateException extends StateException
 * {
 *     public static function forTransition(string $from, string $to): self
 *     {
 *         return new self("Cannot transition from state '{$from}' to '{$to}'");
 *     }
 * }
 * ```
 * @example State conflict
 * ```php
 * final class StateConflictException extends StateException
 * {
 *     public static function detected(string $entity): self
 *     {
 *         return new self("State conflict detected for entity: {$entity}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class StateException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
