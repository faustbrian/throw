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
 * Exception for race condition detection.
 *
 * Thrown when race conditions are detected in concurrent operations,
 * state transitions, or resource access.
 *
 * @example Race condition detected
 * ```php
 * final class RaceConditionDetectedException extends RaceConditionException
 * {
 *     public static function onResource(string $resource): self
 *     {
 *         return new self("Race condition detected on resource: {$resource}");
 *     }
 * }
 * ```
 * @example Version mismatch
 * ```php
 * final class VersionMismatchException extends RaceConditionException
 * {
 *     public static function detected(int $expected, int $actual): self
 *     {
 *         return new self("Version mismatch: expected {$expected}, got {$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RaceConditionException extends ConcurrencyException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
