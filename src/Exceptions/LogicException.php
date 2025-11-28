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
use LogicException as PhpLogicException;

/**
 * Exception that represents error in the program logic.
 *
 * Thrown when there's a flaw in the program's logic that should be fixed during development.
 * These errors typically indicate programming mistakes rather than runtime conditions.
 *
 * @example Invalid state
 * ```php
 * final class InvalidStateException extends LogicException
 * {
 *     public static function forOperation(string $operation, string $state): self
 *     {
 *         return new self("Cannot perform '{$operation}' in state '{$state}'");
 *     }
 * }
 * ```
 * @example Precondition violation
 * ```php
 * final class PreconditionException extends LogicException
 * {
 *     public static function notMet(string $condition): self
 *     {
 *         return new self("Precondition not met: {$condition}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LogicException extends PhpLogicException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
