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
 * Base exception for view rendering failures.
 *
 * Thrown when view rendering fails, views are not found,
 * or view data is invalid.
 *
 * @example View not found
 * ```php
 * final class ViewNotFoundException extends ViewException
 * {
 *     public static function forView(string $view): self
 *     {
 *         return new self("View not found: {$view}");
 *     }
 * }
 * ```
 * @example Rendering failed
 * ```php
 * final class ViewRenderingException extends ViewException
 * {
 *     public static function failed(string $view): self
 *     {
 *         return new self("Failed to render view: {$view}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ViewException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
