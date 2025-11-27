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
 * Base exception for template compilation errors.
 *
 * Thrown when template compilation fails, template syntax is invalid,
 * or template directives encounter errors.
 *
 * @example Syntax error
 * ```php
 * final class TemplateSyntaxException extends TemplateException
 * {
 *     public static function inTemplate(string $template, int $line): self
 *     {
 *         return new self("Syntax error in template '{$template}' at line {$line}");
 *     }
 * }
 * ```
 * @example Compilation failed
 * ```php
 * final class TemplateCompilationException extends TemplateException
 * {
 *     public static function failed(string $template): self
 *     {
 *         return new self("Failed to compile template: {$template}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TemplateException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
