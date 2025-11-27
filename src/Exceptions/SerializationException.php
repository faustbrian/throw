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
 * Base exception for serialization failures.
 *
 * Thrown when serialization operations fail, data cannot be serialized,
 * or serialization operations encounter errors.
 *
 * @example Serialization failed
 * ```php
 * final class SerializationFailedException extends SerializationException
 * {
 *     public static function failed(string $class, string $reason): self
 *     {
 *         return new self("Serialization failed for class '{$class}': {$reason}");
 *     }
 * }
 * ```
 * @example Unsupported serialization format
 * ```php
 * final class UnsupportedSerializationFormatException extends SerializationException
 * {
 *     public static function detected(string $format, array $supportedFormats): self
 *     {
 *         return new self("Unsupported serialization format '{$format}': supported formats are " . implode(', ', $supportedFormats));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SerializationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
