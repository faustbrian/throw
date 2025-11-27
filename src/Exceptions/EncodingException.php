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
 * Base exception for character encoding issues.
 *
 * Thrown when character encoding conversion fails, encoding is invalid,
 * or encoding detection encounters errors.
 *
 * @example Invalid encoding
 * ```php
 * final class InvalidEncodingException extends EncodingException
 * {
 *     public static function forString(string $encoding): self
 *     {
 *         return new self("Invalid character encoding: {$encoding}");
 *     }
 * }
 * ```
 * @example Conversion failed
 * ```php
 * final class EncodingConversionException extends EncodingException
 * {
 *     public static function failed(string $from, string $to): self
 *     {
 *         return new self("Failed to convert encoding from {$from} to {$to}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class EncodingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
