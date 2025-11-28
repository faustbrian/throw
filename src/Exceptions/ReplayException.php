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
 * Exception for replay protection errors.
 *
 * Thrown when replay attacks are detected, replay protection fails,
 * or replay validation encounters errors.
 *
 * @example Replay attack detected
 * ```php
 * final class ReplayAttackException extends ReplayException
 * {
 *     public static function detected(string $token, string $operation): self
 *     {
 *         return new self("Replay attack detected for operation '{$operation}' with token: {$token}");
 *     }
 * }
 * ```
 * @example Replay window expired
 * ```php
 * final class ReplayWindowExpiredException extends ReplayException
 * {
 *     public static function detected(string $token, int $windowSeconds): self
 *     {
 *         return new self("Replay window expired for token '{$token}': window was {$windowSeconds} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ReplayException extends IdempotencyException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
