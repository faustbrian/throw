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
 * Base exception for authentication failures.
 *
 * Thrown when authentication is required but not provided, or when
 * provided credentials are invalid. Maps to HTTP 401 Unauthorized.
 *
 * @example Invalid credentials
 * ```php
 * final class InvalidCredentialsException extends UnauthorizedException
 * {
 *     public static function forUser(string $email): self
 *     {
 *         return new self("Invalid credentials for {$email}");
 *     }
 * }
 * ```
 * @example Missing token
 * ```php
 * final class MissingAuthTokenException extends UnauthorizedException
 * {
 *     public static function required(): self
 *     {
 *         return new self('Authentication token required');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class UnauthorizedException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
