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
 * Base exception for component rendering issues.
 *
 * Thrown when component rendering fails, components are not found,
 * or component properties are invalid.
 *
 * @example Component not found
 * ```php
 * final class ComponentNotFoundException extends ComponentException
 * {
 *     public static function forName(string $component): self
 *     {
 *         return new self("Component not found: {$component}");
 *     }
 * }
 * ```
 * @example Invalid component props
 * ```php
 * final class InvalidComponentPropsException extends ComponentException
 * {
 *     public static function forComponent(string $component, array $missing): self
 *     {
 *         $props = implode(', ', $missing);
 *         return new self("Component '{$component}' missing required props: {$props}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ComponentException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
