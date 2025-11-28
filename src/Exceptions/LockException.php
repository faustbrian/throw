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
 * Base exception for lock acquisition failures.
 *
 * Thrown when lock acquisition fails, locks timeout,
 * or lock operations encounter errors.
 *
 * @example Lock acquisition failed
 * ```php
 * final class LockAcquisitionException extends LockException
 * {
 *     public static function failed(string $lockName, string $reason): self
 *     {
 *         return new self("Lock acquisition failed for '{$lockName}': {$reason}");
 *     }
 * }
 * ```
 * @example Lock timeout
 * ```php
 * final class LockTimeoutException extends LockException
 * {
 *     public static function detected(string $lockName, int $timeoutSeconds): self
 *     {
 *         return new self("Lock '{$lockName}' acquisition timeout after {$timeoutSeconds} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LockException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
