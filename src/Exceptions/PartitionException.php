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
 * Exception for partition management failures.
 *
 * Thrown when partition operations fail, partition rebalancing encounters errors,
 * or partition management fails.
 *
 * @example Partition operation failed
 * ```php
 * final class PartitionOperationException extends PartitionException
 * {
 *     public static function failed(string $operation, string $partition): self
 *     {
 *         return new self("Partition operation '{$operation}' failed for partition: {$partition}");
 *     }
 * }
 * ```
 * @example Partition rebalancing failed
 * ```php
 * final class PartitionRebalancingException extends PartitionException
 * {
 *     public static function failed(string $reason): self
 *     {
 *         return new self("Partition rebalancing failed: {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PartitionException extends ShardingException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
