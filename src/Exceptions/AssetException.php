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
 * Base exception for asset compilation and build errors.
 *
 * Thrown when asset compilation fails (webpack, vite, mix), CSS/JS
 * bundling encounters errors, or asset builds fail.
 *
 * @example Asset compilation failed
 * ```php
 * final class AssetCompilationException extends AssetException
 * {
 *     public static function failed(string $asset): self
 *     {
 *         return new self("Failed to compile asset: {$asset}");
 *     }
 * }
 * ```
 * @example Build error
 * ```php
 * final class AssetBuildException extends AssetException
 * {
 *     public static function failed(string $error): self
 *     {
 *         return new self("Asset build failed: {$error}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AssetException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
