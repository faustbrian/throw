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
 * Exception thrown for Unicode character encoding errors.
 *
 * Thrown when encountering invalid Unicode sequences, character encoding issues,
 * or normalization failures. Distinct from general encoding exceptions.
 *
 * @example Invalid Unicode
 * ```php
 * final class InvalidUnicodeException extends UnicodeException
 * {
 *     public static function sequence(string $sequence): self
 *     {
 *         return new self("Invalid Unicode sequence: {$sequence}");
 *     }
 * }
 * ```
 * @example Encoding conversion failure
 * ```php
 * final class UnicodeConversionException extends UnicodeException
 * {
 *     public static function fromTo(string $from, string $to): self
 *     {
 *         return new self("Failed to convert Unicode from {$from} to {$to}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class UnicodeException extends EncodingException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
