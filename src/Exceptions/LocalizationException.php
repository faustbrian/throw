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
 * Base exception for translation and locale errors.
 *
 * Thrown when localization operations fail, locales are invalid,
 * or locale files cannot be loaded.
 *
 * @example Locale not supported
 * ```php
 * final class UnsupportedLocaleException extends LocalizationException
 * {
 *     public static function forLocale(string $locale): self
 *     {
 *         return new self("Locale not supported: {$locale}");
 *     }
 * }
 * ```
 * @example Locale file missing
 * ```php
 * final class LocaleFileNotFoundException extends LocalizationException
 * {
 *     public static function forPath(string $path): self
 *     {
 *         return new self("Locale file not found: {$path}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LocalizationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
