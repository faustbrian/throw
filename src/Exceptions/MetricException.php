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
 * Base exception for metric collection errors.
 *
 * Thrown when metric collection fails, metrics cannot be sent to
 * monitoring services, or metric processing encounters errors.
 *
 * @example Metric collection failed
 * ```php
 * final class MetricCollectionException extends MetricException
 * {
 *     public static function failed(string $metric): self
 *     {
 *         return new self("Failed to collect metric: {$metric}");
 *     }
 * }
 * ```
 * @example Cannot send metrics
 * ```php
 * final class MetricSendException extends MetricException
 * {
 *     public static function failed(string $service): self
 *     {
 *         return new self("Failed to send metrics to {$service}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MetricException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
