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
 * Exception for routing key calculation errors.
 *
 * Thrown when routing key calculation fails, routing keys are invalid,
 * or routing key operations encounter errors.
 *
 * @example Routing key calculation failed
 * ```php
 * final class RoutingKeyCalculationException extends RoutingKeyException
 * {
 *     public static function failed(mixed $data, string $reason): self
 *     {
 *         return new self("Routing key calculation failed: {$reason}");
 *     }
 * }
 * ```
 * @example Invalid routing key
 * ```php
 * final class InvalidRoutingKeyException extends RoutingKeyException
 * {
 *     public static function detected(string $key, string $reason): self
 *     {
 *         return new self("Invalid routing key '{$key}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RoutingKeyException extends ShardingException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
