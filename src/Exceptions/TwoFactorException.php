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
 * Base exception for 2FA operation failures.
 *
 * Thrown when two-factor authentication operations fail, codes are invalid,
 * or 2FA enrollment encounters errors.
 *
 * @example Invalid 2FA code
 * ```php
 * final class InvalidTwoFactorCodeException extends TwoFactorException
 * {
 *     public static function provided(): self
 *     {
 *         return new self('Invalid two-factor authentication code');
 *     }
 * }
 * ```
 * @example 2FA setup failed
 * ```php
 * final class TwoFactorSetupException extends TwoFactorException
 * {
 *     public static function failed(string $method): self
 *     {
 *         return new self("Failed to setup two-factor authentication using: {$method}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TwoFactorException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
