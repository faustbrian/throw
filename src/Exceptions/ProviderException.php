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
 * Base exception for service provider errors.
 *
 * Thrown when service providers fail, provider registration fails,
 * or provider operations encounter errors.
 *
 * @example Provider not found
 * ```php
 * final class ProviderNotFoundException extends ProviderException
 * {
 *     public static function forName(string $provider): self
 *     {
 *         return new self("Service provider not found: {$provider}");
 *     }
 * }
 * ```
 * @example Provider registration failed
 * ```php
 * final class ProviderRegistrationException extends ProviderException
 * {
 *     public static function failed(string $provider): self
 *     {
 *         return new self("Failed to register service provider: {$provider}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ProviderException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
