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
 * Base exception for data projection failures.
 *
 * Thrown when data projection fails, projection updates encounter errors,
 * or projection operations fail.
 *
 * @example Projection failed
 * ```php
 * final class ProjectionFailedException extends ProjectionException
 * {
 *     public static function forEvent(string $projection, string $event): self
 *     {
 *         return new self("Projection '{$projection}' failed for event: {$event}");
 *     }
 * }
 * ```
 * @example Projection rebuild failed
 * ```php
 * final class ProjectionRebuildException extends ProjectionException
 * {
 *     public static function failed(string $projection, string $reason): self
 *     {
 *         return new self("Projection '{$projection}' rebuild failed: {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ProjectionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
