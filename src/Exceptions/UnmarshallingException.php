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
 * Exception for unmarshalling failures.
 *
 * Thrown when unmarshalling operations fail, data cannot be unmarshalled,
 * or unmarshalling operations encounter errors.
 *
 * @example Unmarshalling failed
 * ```php
 * final class UnmarshallingFailedException extends UnmarshallingException
 * {
 *     public static function failed(string $data, string $targetClass): self
 *     {
 *         return new self("Unmarshalling failed to target class: {$targetClass}");
 *     }
 * }
 * ```
 * @example Invalid unmarshalling data
 * ```php
 * final class InvalidUnmarshallingDataException extends UnmarshallingException
 * {
 *     public static function detected(string $format, string $reason): self
 *     {
 *         return new self("Invalid unmarshalling data for format '{$format}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class UnmarshallingException extends SerializationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
