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
 * Exception for view refresh operation failures.
 *
 * Thrown when view refresh operations fail, refresh scheduling encounters errors,
 * or incremental refresh fails.
 *
 * @example View refresh failed
 * ```php
 * final class ViewRefreshFailedException extends ViewRefreshException
 * {
 *     public static function forView(string $view, string $reason): self
 *     {
 *         return new self("View refresh failed for '{$view}': {$reason}");
 *     }
 * }
 * ```
 * @example Incremental refresh failed
 * ```php
 * final class IncrementalRefreshException extends ViewRefreshException
 * {
 *     public static function failed(string $view, int $fromVersion, int $toVersion): self
 *     {
 *         return new self("Incremental refresh failed for view '{$view}': from version {$fromVersion} to {$toVersion}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ViewRefreshException extends MaterializedViewException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
