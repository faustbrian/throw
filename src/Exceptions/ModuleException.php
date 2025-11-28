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
 * Exception for modular system errors.
 *
 * Thrown when module loading fails, module interface violations occur,
 * or module lifecycle encounters errors.
 *
 * @example Module failed to load
 * ```php
 * final class ModuleLoadException extends ModuleException
 * {
 *     public static function failed(string $module): self
 *     {
 *         return new self("Module failed to load: {$module}");
 *     }
 * }
 * ```
 * @example Module interface violation
 * ```php
 * final class ModuleInterfaceException extends ModuleException
 * {
 *     public static function detected(string $module, string $interface): self
 *     {
 *         return new self("Module '{$module}' violates interface: {$interface}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ModuleException extends PluginException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
