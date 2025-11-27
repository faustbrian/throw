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
 * Base exception for registry operation errors.
 *
 * Thrown when service registry operations fail, registration fails,
 * or deregistration encounters errors.
 *
 * @example Registration failed
 * ```php
 * final class ServiceRegistrationException extends ServiceRegistryException
 * {
 *     public static function failed(string $service): self
 *     {
 *         return new self("Failed to register service: {$service}");
 *     }
 * }
 * ```
 * @example Deregistration failed
 * ```php
 * final class ServiceDeregistrationException extends ServiceRegistryException
 * {
 *     public static function failed(string $service): self
 *     {
 *         return new self("Failed to deregister service: {$service}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ServiceRegistryException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
