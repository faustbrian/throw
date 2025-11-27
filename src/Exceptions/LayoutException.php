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
 * Exception for layout composition errors.
 *
 * Thrown when layout composition fails, layout sections are missing,
 * or layout inheritance encounters errors.
 *
 * @example Layout composition failed
 * ```php
 * final class LayoutCompositionException extends LayoutException
 * {
 *     public static function failed(string $layout): self
 *     {
 *         return new self("Layout composition failed: {$layout}");
 *     }
 * }
 * ```
 * @example Missing layout section
 * ```php
 * final class MissingLayoutSectionException extends LayoutException
 * {
 *     public static function detected(string $layout, string $section): self
 *     {
 *         return new self("Layout '{$layout}' missing required section: {$section}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LayoutException extends RenderException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
