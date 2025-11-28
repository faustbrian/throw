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
 * Exception for service discovery errors.
 *
 * Thrown when service discovery fails, services are not found,
 * or discovery operations encounter errors.
 *
 * @example Service discovery failed
 * ```php
 * final class ServiceDiscoveryFailedException extends DiscoveryException
 * {
 *     public static function failed(string $service, string $reason): self
 *     {
 *         return new self("Service discovery failed for '{$service}': {$reason}");
 *     }
 * }
 * ```
 * @example Service not found
 * ```php
 * final class ServiceNotFoundException extends DiscoveryException
 * {
 *     public static function detected(string $service): self
 *     {
 *         return new self("Service not found: {$service}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DiscoveryException extends RegistryException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
