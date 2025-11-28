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
 * Base exception for checkpoint creation failures.
 *
 * Thrown when checkpoint creation fails, checkpoint restoration encounters errors,
 * or checkpoint operations fail.
 *
 * @example Checkpoint creation failed
 * ```php
 * final class CheckpointCreationException extends CheckpointException
 * {
 *     public static function failed(string $checkpoint, string $reason): self
 *     {
 *         return new self("Checkpoint '{$checkpoint}' creation failed: {$reason}");
 *     }
 * }
 * ```
 * @example Checkpoint not found
 * ```php
 * final class CheckpointNotFoundException extends CheckpointException
 * {
 *     public static function detected(string $checkpoint): self
 *     {
 *         return new self("Checkpoint not found: {$checkpoint}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CheckpointException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
