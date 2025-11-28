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
 * Base exception for data loading errors.
 *
 * Thrown when data loading fails, target destination is unavailable,
 * or data writing encounters errors.
 *
 * @example Load failed
 * ```php
 * final class LoadFailedException extends LoadException
 * {
 *     public static function toDestination(string $destination): self
 *     {
 *         return new self("Data loading failed to destination: {$destination}");
 *     }
 * }
 * ```
 * @example Destination unavailable
 * ```php
 * final class DestinationUnavailableException extends LoadException
 * {
 *     public static function detected(string $destination): self
 *     {
 *         return new self("Load destination unavailable: {$destination}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LoadException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
