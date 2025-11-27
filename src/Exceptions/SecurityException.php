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
 * Base exception for security violations.
 *
 * Thrown when security checks fail, suspicious activity is detected,
 * or security policies are violated.
 *
 * @example CSRF token mismatch
 * ```php
 * final class CsrfException extends SecurityException
 * {
 *     public static function tokenMismatch(): self
 *     {
 *         return new self('CSRF token mismatch');
 *     }
 * }
 * ```
 * @example Suspicious activity
 * ```php
 * final class SuspiciousActivityException extends SecurityException
 * {
 *     public static function detected(string $reason): self
 *     {
 *         return new self("Suspicious activity detected: {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SecurityException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
