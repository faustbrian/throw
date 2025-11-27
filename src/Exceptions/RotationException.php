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
 * Base exception for secret rotation failures.
 *
 * Thrown when secret rotation fails, rotation schedule is invalid,
 * or rotation operations encounter errors.
 *
 * @example Rotation failed
 * ```php
 * final class RotationFailedException extends RotationException
 * {
 *     public static function forSecret(string $secret): self
 *     {
 *         return new self("Failed to rotate secret: {$secret}");
 *     }
 * }
 * ```
 * @example Rotation overdue
 * ```php
 * final class RotationOverdueException extends RotationException
 * {
 *     public static function detected(string $secret, int $days): self
 *     {
 *         return new self("Secret '{$secret}' rotation is {$days} days overdue");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RotationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
