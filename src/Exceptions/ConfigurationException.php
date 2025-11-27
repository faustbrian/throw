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
 * Base exception for configuration errors.
 *
 * Thrown when application or service configuration is invalid, missing,
 * or incompatible. Helps distinguish config errors from runtime errors.
 *
 * @example Missing configuration
 * ```php
 * final class MissingConfigException extends ConfigurationException
 * {
 *     public static function forKey(string $key): self
 *     {
 *         return new self("Missing required configuration: {$key}");
 *     }
 * }
 * ```
 * @example Invalid configuration
 * ```php
 * final class InvalidConfigException extends ConfigurationException
 * {
 *     public static function forService(string $service): self
 *     {
 *         return new self("Invalid configuration for {$service}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConfigurationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
