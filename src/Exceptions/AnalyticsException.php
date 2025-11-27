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
 * Base exception for analytics tracking failures.
 *
 * Thrown when analytics tracking fails, analytics events cannot be sent,
 * or analytics service operations encounter errors.
 *
 * @example Event tracking failed
 * ```php
 * final class AnalyticsEventException extends AnalyticsException
 * {
 *     public static function failed(string $event): self
 *     {
 *         return new self("Failed to track analytics event: {$event}");
 *     }
 * }
 * ```
 * @example Analytics service unavailable
 * ```php
 * final class AnalyticsServiceException extends AnalyticsException
 * {
 *     public static function unavailable(string $service): self
 *     {
 *         return new self("Analytics service unavailable: {$service}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AnalyticsException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
