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
 * Base exception for canary deployment failures.
 *
 * Thrown when canary deployments fail, canary metrics are unhealthy,
 * or canary analysis encounters errors.
 *
 * @example Canary failed
 * ```php
 * final class CanaryFailedException extends CanaryException
 * {
 *     public static function withMetrics(string $version, array $metrics): self
 *     {
 *         $metricList = implode(', ', $metrics);
 *         return new self("Canary deployment for version '{$version}' failed: {$metricList}");
 *     }
 * }
 * ```
 * @example Canary threshold exceeded
 * ```php
 * final class CanaryThresholdExceededException extends CanaryException
 * {
 *     public static function forMetric(string $metric, float $threshold, float $actual): self
 *     {
 *         return new self("Canary metric '{$metric}' exceeded threshold: {$actual} > {$threshold}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CanaryException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
