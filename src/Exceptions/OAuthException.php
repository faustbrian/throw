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
 * Base exception for OAuth flow errors.
 *
 * Thrown when OAuth authentication flows fail, tokens are invalid,
 * or authorization is denied.
 *
 * @example Authorization denied
 * ```php
 * final class OAuthAuthorizationException extends OAuthException
 * {
 *     public static function denied(string $provider): self
 *     {
 *         return new self("OAuth authorization denied by {$provider}");
 *     }
 * }
 * ```
 * @example Invalid token
 * ```php
 * final class OAuthTokenException extends OAuthException
 * {
 *     public static function invalid(): self
 *     {
 *         return new self('OAuth token is invalid or expired');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class OAuthException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
