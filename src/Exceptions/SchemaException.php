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
 * Exception for database schema errors.
 *
 * Thrown when schema validation fails, schema changes conflict,
 * or schema operations encounter errors.
 *
 * @example Schema validation failed
 * ```php
 * final class SchemaValidationException extends SchemaException
 * {
 *     public static function failed(string $table, array $errors): self
 *     {
 *         return new self("Schema validation failed for table '{$table}': " . json_encode($errors));
 *     }
 * }
 * ```
 * @example Schema conflict detected
 * ```php
 * final class SchemaConflictException extends SchemaException
 * {
 *     public static function detected(string $table, string $conflict): self
 *     {
 *         return new self("Schema conflict detected for table '{$table}': {$conflict}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SchemaException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
