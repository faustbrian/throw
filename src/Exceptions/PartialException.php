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
 * Exception for template partial loading errors.
 *
 * Thrown when template partials fail to load, partials are not found,
 * or partial inclusion encounters errors.
 *
 * @example Partial loading failed
 * ```php
 * final class PartialLoadException extends PartialException
 * {
 *     public static function failed(string $partial): self
 *     {
 *         return new self("Partial failed to load: {$partial}");
 *     }
 * }
 * ```
 * @example Partial not found
 * ```php
 * final class PartialNotFoundException extends PartialException
 * {
 *     public static function detected(string $partial, array $searchPaths): self
 *     {
 *         return new self("Partial '{$partial}' not found in paths: " . implode(', ', $searchPaths));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PartialException extends RenderException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
