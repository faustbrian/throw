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
 * Base exception for dependency injection container errors.
 *
 * Thrown when container operations fail (service resolution, binding,
 * instantiation, etc.) or dependencies cannot be resolved.
 *
 * @example Service not found
 * ```php
 * final class ServiceNotFoundException extends ContainerException
 * {
 *     public static function notBound(string $service): self
 *     {
 *         return new self("Service not bound in container: {$service}");
 *     }
 * }
 * ```
 * @example Resolution failed
 * ```php
 * final class ResolutionException extends ContainerException
 * {
 *     public static function failed(string $service): self
 *     {
 *         return new self("Failed to resolve service: {$service}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ContainerException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
