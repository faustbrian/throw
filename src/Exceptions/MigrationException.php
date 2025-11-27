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
 * Base exception for database migration failures.
 *
 * Thrown when database migrations fail to run, rollback, or when
 * migration state is inconsistent.
 *
 * @example Migration failed
 * ```php
 * final class MigrationFailedException extends MigrationException
 * {
 *     public static function cannotRun(string $migration): self
 *     {
 *         return new self("Failed to run migration: {$migration}");
 *     }
 * }
 * ```
 * @example Rollback failed
 * ```php
 * final class RollbackFailedException extends MigrationException
 * {
 *     public static function cannotRollback(string $migration): self
 *     {
 *         return new self("Failed to rollback migration: {$migration}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MigrationException extends InfrastructureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
