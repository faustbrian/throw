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
 * Base exception for saga pattern transaction errors.
 *
 * Thrown when saga transactions fail, saga compensation fails,
 * or distributed transaction coordination encounters errors.
 *
 * @example Saga failed
 * ```php
 * final class SagaFailedException extends SagaException
 * {
 *     public static function atStep(string $saga, string $step): self
 *     {
 *         return new self("Saga '{$saga}' failed at step: {$step}");
 *     }
 * }
 * ```
 * @example Compensation failed
 * ```php
 * final class CompensationFailedException extends SagaException
 * {
 *     public static function forStep(string $step): self
 *     {
 *         return new self("Saga compensation failed for step: {$step}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SagaException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
