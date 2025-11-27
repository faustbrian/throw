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
 * Base exception for field mapping errors.
 *
 * Thrown when field mapping fails, mappings are invalid,
 * or mapping operations encounter errors.
 *
 * @example Mapping failed
 * ```php
 * final class MappingFailedException extends MappingException
 * {
 *     public static function forField(string $sourceField, string $targetField): self
 *     {
 *         return new self("Field mapping failed: '{$sourceField}' -> '{$targetField}'");
 *     }
 * }
 * ```
 * @example Missing required mapping
 * ```php
 * final class MissingMappingException extends MappingException
 * {
 *     public static function detected(string $field, array $availableFields): self
 *     {
 *         return new self("Missing mapping for required field '{$field}': available fields are " . implode(', ', $availableFields));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MappingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
