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
 * Base exception for XML parsing failures.
 *
 * Thrown when XML parsing fails, XML is malformed,
 * or XML operations encounter errors.
 *
 * @example XML parsing failed
 * ```php
 * final class XmlParsingException extends XmlException
 * {
 *     public static function failed(string $xml, int $line, string $error): self
 *     {
 *         return new self("XML parsing failed at line {$line}: {$error}");
 *     }
 * }
 * ```
 * @example Invalid XML structure
 * ```php
 * final class InvalidXmlStructureException extends XmlException
 * {
 *     public static function detected(string $expectedRoot, string $actualRoot): self
 *     {
 *         return new self("Invalid XML structure: expected root '{$expectedRoot}', got '{$actualRoot}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class XmlException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
