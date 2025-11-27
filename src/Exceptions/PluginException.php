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
 * Base exception for plugin system errors.
 *
 * Thrown when plugin loading fails, plugin dependencies are missing,
 * or plugin execution encounters errors.
 *
 * @example Plugin failed to load
 * ```php
 * final class PluginLoadException extends PluginException
 * {
 *     public static function failed(string $plugin): self
 *     {
 *         return new self("Plugin failed to load: {$plugin}");
 *     }
 * }
 * ```
 * @example Plugin dependency missing
 * ```php
 * final class PluginDependencyException extends PluginException
 * {
 *     public static function missing(string $plugin, string $dependency): self
 *     {
 *         return new self("Plugin '{$plugin}' missing dependency: {$dependency}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PluginException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
