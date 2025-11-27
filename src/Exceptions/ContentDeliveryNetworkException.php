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
 * Base exception for CDN operation failures.
 *
 * Thrown when CDN operations fail, cache purging fails,
 * or content distribution encounters errors.
 *
 * @example Purge failed
 * ```php
 * final class CdnPurgeException extends ContentDeliveryNetworkException
 * {
 *     public static function failed(string $url): self
 *     {
 *         return new self("Failed to purge CDN cache for URL: {$url}");
 *     }
 * }
 * ```
 * @example Distribution unavailable
 * ```php
 * final class DistributionUnavailableException extends ContentDeliveryNetworkException
 * {
 *     public static function forDistribution(string $distribution): self
 *     {
 *         return new self("CDN distribution unavailable: {$distribution}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ContentDeliveryNetworkException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
