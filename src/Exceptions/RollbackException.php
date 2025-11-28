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
 * Base exception for rollback operation errors.
 *
 * Thrown when rollback operations fail, rollback cannot start,
 * or rollback encounters errors during execution.
 *
 * @example Rollback failed
 * ```php
 * final class RollbackFailedException extends RollbackException
 * {
 *     public static function toVersion(string $from, string $to): self
 *     {
 *         return new self("Failed to rollback from version {$from} to {$to}");
 *     }
 * }
 * ```
 * @example No rollback target
 * ```php
 * final class NoRollbackTargetException extends RollbackException
 * {
 *     public static function available(): self
 *     {
 *         return new self('No previous version available for rollback');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RollbackException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
