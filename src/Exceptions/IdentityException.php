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
 * Base exception for identity management errors.
 *
 * Thrown when identity operations fail, identity verification fails,
 * or identity provider operations encounter errors.
 *
 * @example Identity verification failed
 * ```php
 * final class IdentityVerificationException extends IdentityException
 * {
 *     public static function failed(string $identifier): self
 *     {
 *         return new self("Identity verification failed for: {$identifier}");
 *     }
 * }
 * ```
 * @example Identity provider unavailable
 * ```php
 * final class IdentityProviderException extends IdentityException
 * {
 *     public static function unavailable(string $provider): self
 *     {
 *         return new self("Identity provider unavailable: {$provider}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class IdentityException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
