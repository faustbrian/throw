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
 * Base exception for logger initialization failures.
 *
 * Thrown when logger initialization fails, logger configuration is invalid,
 * or logger setup encounters errors.
 *
 * @example Logger initialization failed
 * ```php
 * final class LoggerInitializationException extends LoggerException
 * {
 *     public static function failed(string $logger): self
 *     {
 *         return new self("Logger initialization failed: {$logger}");
 *     }
 * }
 * ```
 * @example Invalid logger configuration
 * ```php
 * final class InvalidLoggerConfigException extends LoggerException
 * {
 *     public static function detected(string $logger, string $issue): self
 *     {
 *         return new self("Invalid logger configuration for '{$logger}': {$issue}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LoggerException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
