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
 * Exception for command execution errors.
 *
 * Thrown when command execution fails, commands cannot be parsed,
 * or command arguments are invalid.
 *
 * @example Invalid arguments
 * ```php
 * final class InvalidCommandArgumentsException extends CommandException
 * {
 *     public static function missing(string $argument): self
 *     {
 *         return new self("Required command argument missing: {$argument}");
 *     }
 * }
 * ```
 * @example Command aborted
 * ```php
 * final class CommandAbortedException extends CommandException
 * {
 *     public static function byUser(string $command): self
 *     {
 *         return new self("Command '{$command}' aborted by user");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CommandException extends ConsoleException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
