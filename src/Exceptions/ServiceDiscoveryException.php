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
 * Base exception for service discovery failures.
 *
 * Thrown when service discovery fails, services cannot be found,
 * or service lookup encounters errors.
 *
 * @example Service not discovered
 * ```php
 * final class ServiceNotDiscoveredException extends ServiceDiscoveryException
 * {
 *     public static function forName(string $service): self
 *     {
 *         return new self("Service not discovered: {$service}");
 *     }
 * }
 * ```
 * @example Discovery timeout
 * ```php
 * final class DiscoveryTimeoutException extends ServiceDiscoveryException
 * {
 *     public static function after(string $service, int $timeout): self
 *     {
 *         return new self("Service discovery timed out for '{$service}' after {$timeout} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ServiceDiscoveryException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
