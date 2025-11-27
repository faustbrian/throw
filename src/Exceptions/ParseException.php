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
 * Base exception for parsing failures.
 *
 * Thrown when parsing operations fail (JSON, XML, CSV, YAML, etc.).
 * More specific than SerializationException for parse-time errors.
 *
 * @example JSON parse error
 * ```php
 * final class JsonParseException extends ParseException
 * {
 *     public static function invalidSyntax(string $error): self
 *     {
 *         return new self("JSON parse error: {$error}");
 *     }
 * }
 * ```
 * @example XML parse error
 * ```php
 * final class XmlParseException extends ParseException
 * {
 *     public static function malformed(int $line): self
 *     {
 *         return new self("XML parse error at line {$line}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ParseException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
