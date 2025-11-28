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
 * Base exception for external process execution errors.
 *
 * Thrown when external process execution fails, processes timeout,
 * or process output cannot be captured.
 *
 * @example Process failed
 * ```php
 * final class ProcessFailedException extends ProcessException
 * {
 *     public static function withExitCode(string $command, int $exitCode): self
 *     {
 *         return new self("Process '{$command}' failed with exit code: {$exitCode}");
 *     }
 * }
 * ```
 * @example Process timeout
 * ```php
 * final class ProcessTimeoutException extends ProcessException
 * {
 *     public static function after(string $command, int $timeout): self
 *     {
 *         return new self("Process '{$command}' timed out after {$timeout} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ProcessException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
