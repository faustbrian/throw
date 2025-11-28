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
 * Exception for extension system failures.
 *
 * Thrown when extension registration fails, extension conflicts occur,
 * or extension initialization encounters errors.
 *
 * @example Extension failed
 * ```php
 * final class ExtensionFailedException extends ExtensionException
 * {
 *     public static function toRegister(string $extension): self
 *     {
 *         return new self("Extension failed to register: {$extension}");
 *     }
 * }
 * ```
 * @example Extension conflict
 * ```php
 * final class ExtensionConflictException extends ExtensionException
 * {
 *     public static function detected(string $extension1, string $extension2): self
 *     {
 *         return new self("Extension conflict detected between '{$extension1}' and '{$extension2}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ExtensionException extends PluginException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
