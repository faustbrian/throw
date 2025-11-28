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
 * Base exception for accessibility requirement violations.
 *
 * Thrown when accessibility standards are violated (WCAG, ADA, etc.),
 * accessibility features fail, or accessibility checks encounter errors.
 *
 * @example WCAG violation
 * ```php
 * final class WcagViolationException extends AccessibilityException
 * {
 *     public static function detected(string $element, string $criteria): self
 *     {
 *         return new self("WCAG violation on element '{$element}': {$criteria}");
 *     }
 * }
 * ```
 * @example Missing alt text
 * ```php
 * final class MissingAltTextException extends AccessibilityException
 * {
 *     public static function forImage(string $src): self
 *     {
 *         return new self("Missing alt text for image: {$src}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AccessibilityException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
