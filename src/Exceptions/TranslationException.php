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

/**
 * Exception for missing translation keys.
 *
 * Thrown when translation keys are not found, translation values
 * are missing, or translation loading fails.
 *
 * @example Translation key missing
 * ```php
 * final class TranslationKeyNotFoundException extends TranslationException
 * {
 *     public static function forKey(string $key, string $locale): self
 *     {
 *         return new self("Translation key '{$key}' not found for locale: {$locale}");
 *     }
 * }
 * ```
 * @example Translation loading failed
 * ```php
 * final class TranslationLoadException extends TranslationException
 * {
 *     public static function failed(string $group, string $locale): self
 *     {
 *         return new self("Failed to load translations for '{$group}' in locale: {$locale}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TranslationException extends LocalizationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
