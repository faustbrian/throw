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
 * Base exception for template rendering failures.
 *
 * Thrown when template rendering fails, template syntax is invalid,
 * or rendering operations encounter errors.
 *
 * @example Rendering failed
 * ```php
 * final class RenderingFailedException extends RenderException
 * {
 *     public static function forTemplate(string $template): self
 *     {
 *         return new self("Template rendering failed: {$template}");
 *     }
 * }
 * ```
 * @example Template syntax error
 * ```php
 * final class TemplateSyntaxException extends RenderException
 * {
 *     public static function detected(string $template, int $line, string $error): self
 *     {
 *         return new self("Syntax error in template '{$template}' at line {$line}: {$error}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RenderException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
