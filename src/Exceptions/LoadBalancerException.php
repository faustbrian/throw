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
 * Base exception for load balancing errors.
 *
 * Thrown when load balancing fails, no healthy backends are available,
 * or load balancer operations encounter errors.
 *
 * @example Load balancing failed
 * ```php
 * final class LoadBalancingFailedException extends LoadBalancerException
 * {
 *     public static function failed(string $loadBalancer, string $reason): self
 *     {
 *         return new self("Load balancing failed for '{$loadBalancer}': {$reason}");
 *     }
 * }
 * ```
 * @example No healthy backends
 * ```php
 * final class NoHealthyBackendsException extends LoadBalancerException
 * {
 *     public static function detected(string $loadBalancer, int $totalBackends): self
 *     {
 *         return new self("No healthy backends available for load balancer '{$loadBalancer}': {$totalBackends} total backends");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LoadBalancerException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
