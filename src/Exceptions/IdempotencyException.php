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
 * Base exception for idempotency key errors.
 *
 * Thrown when idempotency key validation fails, duplicate operations are detected,
 * or idempotency operations encounter errors.
 *
 * @example Idempotency key conflict
 * ```php
 * final class IdempotencyKeyConflictException extends IdempotencyException
 * {
 *     public static function detected(string $key, string $operation): self
 *     {
 *         return new self("Idempotency key '{$key}' conflict for operation: {$operation}");
 *     }
 * }
 * ```
 * @example Invalid idempotency key
 * ```php
 * final class InvalidIdempotencyKeyException extends IdempotencyException
 * {
 *     public static function detected(string $key, string $reason): self
 *     {
 *         return new self("Invalid idempotency key '{$key}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class IdempotencyException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
