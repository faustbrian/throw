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
 * Base exception for YAML parsing errors.
 *
 * Thrown when YAML parsing fails, YAML is malformed,
 * or YAML operations encounter errors.
 *
 * @example YAML parsing failed
 * ```php
 * final class YamlParsingException extends YamlException
 * {
 *     public static function failed(string $yaml, int $line, string $error): self
 *     {
 *         return new self("YAML parsing failed at line {$line}: {$error}");
 *     }
 * }
 * ```
 * @example Invalid YAML syntax
 * ```php
 * final class InvalidYamlSyntaxException extends YamlException
 * {
 *     public static function detected(string $yaml, string $error): self
 *     {
 *         return new self("Invalid YAML syntax: {$error}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class YamlException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
