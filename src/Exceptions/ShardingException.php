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
 * Base exception for sharding operation errors.
 *
 * Thrown when sharding operations fail, shard allocation encounters errors,
 * or shard management fails.
 *
 * @example Sharding operation failed
 * ```php
 * final class ShardingOperationException extends ShardingException
 * {
 *     public static function failed(string $operation, string $shard): self
 *     {
 *         return new self("Sharding operation '{$operation}' failed for shard: {$shard}");
 *     }
 * }
 * ```
 * @example Shard not found
 * ```php
 * final class ShardNotFoundException extends ShardingException
 * {
 *     public static function detected(string $shardId): self
 *     {
 *         return new self("Shard not found: {$shardId}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ShardingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
