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
 * Base exception for worker process failures.
 *
 * Thrown when worker processes fail, workers cannot start,
 * or worker lifecycle operations encounter errors.
 *
 * @example Worker failed
 * ```php
 * final class WorkerFailedException extends WorkerException
 * {
 *     public static function withError(string $worker, string $error): self
 *     {
 *         return new self("Worker '{$worker}' failed: {$error}");
 *     }
 * }
 * ```
 * @example Worker timeout
 * ```php
 * final class WorkerTimeoutException extends WorkerException
 * {
 *     public static function after(string $worker, int $timeout): self
 *     {
 *         return new self("Worker '{$worker}' timed out after {$timeout} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class WorkerException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
