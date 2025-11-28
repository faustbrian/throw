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
 * Base exception for debug mode errors.
 *
 * Thrown when debug operations fail, debug mode is misconfigured,
 * or debugging tools encounter errors.
 *
 * @example Debug mode disabled
 * ```php
 * final class DebugModeDisabledException extends DebugException
 * {
 *     public static function detected(): self
 *     {
 *         return new self('Debug mode is disabled in this environment');
 *     }
 * }
 * ```
 * @example Debug data collection failed
 * ```php
 * final class DebugDataCollectionException extends DebugException
 * {
 *     public static function failed(string $collector): self
 *     {
 *         return new self("Failed to collect debug data from: {$collector}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DebugException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
