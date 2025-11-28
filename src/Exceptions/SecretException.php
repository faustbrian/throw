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
 * Base exception for secret retrieval failures.
 *
 * Thrown when secret retrieval fails, secrets are not found,
 * or secret access encounters errors.
 *
 * @example Secret not found
 * ```php
 * final class SecretNotFoundException extends SecretException
 * {
 *     public static function forKey(string $key): self
 *     {
 *         return new self("Secret not found: {$key}");
 *     }
 * }
 * ```
 * @example Secret access denied
 * ```php
 * final class SecretAccessDeniedException extends SecretException
 * {
 *     public static function forKey(string $key, string $user): self
 *     {
 *         return new self("Access denied to secret '{$key}' for user: {$user}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SecretException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
