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
 * Exception for log formatting errors.
 *
 * Thrown when log formatting fails, formatter configuration is invalid,
 * or format templates encounter errors.
 *
 * @example Formatting failed
 * ```php
 * final class FormattingFailedException extends FormatterException
 * {
 *     public static function forLog(string $formatter, string $message): self
 *     {
 *         return new self("Log formatting failed using '{$formatter}': {$message}");
 *     }
 * }
 * ```
 * @example Invalid format template
 * ```php
 * final class InvalidFormatTemplateException extends FormatterException
 * {
 *     public static function detected(string $template, string $error): self
 *     {
 *         return new self("Invalid format template '{$template}': {$error}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FormatterException extends LoggerException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
