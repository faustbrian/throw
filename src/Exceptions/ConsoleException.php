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
 * Base exception for console command failures.
 *
 * Thrown when console commands fail, command execution encounters errors,
 * or console output cannot be generated.
 *
 * @example Command failed
 * ```php
 * final class ConsoleCommandFailedException extends ConsoleException
 * {
 *     public static function withExitCode(string $command, int $exitCode): self
 *     {
 *         return new self("Console command '{$command}' failed with exit code: {$exitCode}");
 *     }
 * }
 * ```
 * @example Command not found
 * ```php
 * final class CommandNotFoundException extends ConsoleException
 * {
 *     public static function forName(string $name): self
 *     {
 *         return new self("Console command not found: {$name}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConsoleException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
