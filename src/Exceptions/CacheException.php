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
 * Base exception for cache operation failures.
 *
 * Thrown when cache operations (get, set, delete, clear) fail or when
 * cache connections are lost.
 *
 * @example Cache write failed
 * ```php
 * final class CacheWriteException extends CacheException
 * {
 *     public static function failed(string $key): self
 *     {
 *         return new self("Failed to write cache key: {$key}");
 *     }
 * }
 * ```
 * @example Cache connection lost
 * ```php
 * final class CacheConnectionException extends CacheException
 * {
 *     public static function lost(string $driver): self
 *     {
 *         return new self("Lost connection to {$driver} cache");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CacheException extends InfrastructureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
