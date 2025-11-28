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
 * Base exception for driver errors.
 *
 * Thrown when drivers fail, driver initialization fails,
 * or driver operations encounter errors.
 *
 * @example Driver not found
 * ```php
 * final class DriverNotFoundException extends DriverException
 * {
 *     public static function forType(string $type): self
 *     {
 *         return new self("Driver not found for type: {$type}");
 *     }
 * }
 * ```
 * @example Driver initialization failed
 * ```php
 * final class DriverInitializationException extends DriverException
 * {
 *     public static function failed(string $driver, string $reason): self
 *     {
 *         return new self("Failed to initialize driver '{$driver}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DriverException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
