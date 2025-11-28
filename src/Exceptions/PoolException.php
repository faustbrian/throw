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
 * Exception for resource pool management errors.
 *
 * Thrown when resource pool operations fail, pools are exhausted,
 * or pool management encounters errors.
 *
 * @example Pool exhausted
 * ```php
 * final class PoolExhaustedException extends PoolException
 * {
 *     public static function detected(string $pool, int $size): self
 *     {
 *         return new self("Resource pool '{$pool}' exhausted: all {$size} resources in use");
 *     }
 * }
 * ```
 * @example Pool initialization failed
 * ```php
 * final class PoolInitializationException extends PoolException
 * {
 *     public static function failed(string $pool, string $reason): self
 *     {
 *         return new self("Pool '{$pool}' initialization failed: {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PoolException extends ResourceException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
