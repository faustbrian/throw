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
 * Base exception for deprecated functionality.
 *
 * Thrown when deprecated methods, features, or APIs are used. Helps
 * communicate deprecation more clearly than generic exceptions.
 *
 * @example Deprecated method
 * ```php
 * final class DeprecatedMethodException extends DeprecatedException
 * {
 *     public static function useInstead(string $old, string $new): self
 *     {
 *         return new self("{$old} is deprecated, use {$new} instead");
 *     }
 * }
 * ```
 * @example Deprecated API version
 * ```php
 * final class DeprecatedApiException extends DeprecatedException
 * {
 *     public static function version(string $version): self
 *     {
 *         return new self("API version {$version} is deprecated");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DeprecatedException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
