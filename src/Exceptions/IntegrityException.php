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
 * Base exception for data integrity violations.
 *
 * Thrown when data integrity checks fail, referential integrity is violated,
 * or data consistency is compromised.
 *
 * @example Referential integrity violation
 * ```php
 * final class ReferentialIntegrityException extends IntegrityException
 * {
 *     public static function violated(string $table, string $column): self
 *     {
 *         return new self("Referential integrity violated: {$table}.{$column}");
 *     }
 * }
 * ```
 * @example Data inconsistency
 * ```php
 * final class DataInconsistencyException extends IntegrityException
 * {
 *     public static function detected(string $entity): self
 *     {
 *         return new self("Data inconsistency detected for entity: {$entity}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class IntegrityException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
