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
 * Base exception for cookie operations failures.
 *
 * Thrown when cookie operations fail, cookies cannot be set,
 * or cookie parsing encounters errors.
 *
 * @example Cookie too large
 * ```php
 * final class CookieTooLargeException extends CookieException
 * {
 *     public static function forSize(int $size, int $max): self
 *     {
 *         return new self("Cookie size {$size} bytes exceeds maximum {$max} bytes");
 *     }
 * }
 * ```
 * @example Cookie security violation
 * ```php
 * final class InsecureCookieException extends CookieException
 * {
 *     public static function notSecure(string $name): self
 *     {
 *         return new self("Cookie '{$name}' must be sent over HTTPS");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CookieException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
