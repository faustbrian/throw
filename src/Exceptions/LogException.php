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
 * Base exception for logging failures.
 *
 * Thrown when logging operations fail, log handlers encounter errors,
 * or log writing fails.
 *
 * @example Log write failed
 * ```php
 * final class LogWriteException extends LogException
 * {
 *     public static function failed(string $channel): self
 *     {
 *         return new self("Failed to write to log channel: {$channel}");
 *     }
 * }
 * ```
 * @example Log handler error
 * ```php
 * final class LogHandlerException extends LogException
 * {
 *     public static function unavailable(string $handler): self
 *     {
 *         return new self("Log handler unavailable: {$handler}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LogException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
