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
 * Base exception for digital signature verification failures.
 *
 * Thrown when signature verification fails, signatures are invalid,
 * or signing operations encounter errors.
 *
 * @example Signature verification failed
 * ```php
 * final class InvalidSignatureException extends SignatureException
 * {
 *     public static function forPayload(string $payload): self
 *     {
 *         return new self("Invalid signature for payload: {$payload}");
 *     }
 * }
 * ```
 * @example Signature expired
 * ```php
 * final class SignatureExpiredException extends SignatureException
 * {
 *     public static function withTimestamp(int $timestamp): self
 *     {
 *         return new self("Signature expired at timestamp: {$timestamp}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SignatureException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
