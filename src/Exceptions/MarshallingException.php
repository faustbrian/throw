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
 * Exception for marshalling operation errors.
 *
 * Thrown when marshalling operations fail, data cannot be marshalled,
 * or marshalling operations encounter errors.
 *
 * @example Marshalling failed
 * ```php
 * final class MarshallingFailedException extends MarshallingException
 * {
 *     public static function failed(string $data, string $format): self
 *     {
 *         return new self("Marshalling failed for data to format: {$format}");
 *     }
 * }
 * ```
 * @example Invalid marshalling schema
 * ```php
 * final class InvalidMarshallingSchemaException extends MarshallingException
 * {
 *     public static function detected(string $schema, string $reason): self
 *     {
 *         return new self("Invalid marshalling schema '{$schema}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MarshallingException extends SerializationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
