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
 * Thrown when data integrity constraints are violated (foreign keys,
 * unique constraints, checksums, etc.).
 *
 * @example Foreign key violation
 * ```php
 * final class ForeignKeyException extends DataIntegrityException
 * {
 *     public static function violation(string $table, string $key): self
 *     {
 *         return new self("Foreign key violation on {$table}.{$key}");
 *     }
 * }
 * ```
 * @example Checksum mismatch
 * ```php
 * final class ChecksumException extends DataIntegrityException
 * {
 *     public static function mismatch(): self
 *     {
 *         return new self('Data checksum verification failed');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DataIntegrityException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
