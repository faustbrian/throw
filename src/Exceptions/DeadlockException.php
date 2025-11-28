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
 * Exception for deadlock detection.
 *
 * Thrown when deadlocks are detected in concurrent operations,
 * database transactions, or resource locking.
 *
 * @example Database deadlock
 * ```php
 * final class DatabaseDeadlockException extends DeadlockException
 * {
 *     public static function detected(array $tables): self
 *     {
 *         $tableList = implode(', ', $tables);
 *         return new self("Deadlock detected on tables: {$tableList}");
 *     }
 * }
 * ```
 * @example Lock timeout
 * ```php
 * final class LockTimeoutException extends DeadlockException
 * {
 *     public static function waiting(string $resource, int $timeout): self
 *     {
 *         return new self("Lock timeout waiting for '{$resource}' after {$timeout} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DeadlockException extends ConcurrencyException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
