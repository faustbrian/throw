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
 * Base exception for session handling errors.
 *
 * Thrown when session operations fail, session data is invalid,
 * or session storage encounters errors.
 *
 * @example Session not started
 * ```php
 * final class SessionNotStartedException extends SessionException
 * {
 *     public static function create(): self
 *     {
 *         return new self('Session has not been started');
 *     }
 * }
 * ```
 * @example Session expired
 * ```php
 * final class SessionExpiredException extends SessionException
 * {
 *     public static function forId(string $id): self
 *     {
 *         return new self("Session expired: {$id}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SessionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
