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
 * Base exception for dashboard rendering errors.
 *
 * Thrown when dashboard rendering fails, dashboard widgets fail,
 * or dashboard data loading encounters errors.
 *
 * @example Dashboard rendering failed
 * ```php
 * final class DashboardRenderingException extends DashboardException
 * {
 *     public static function failed(string $dashboard): self
 *     {
 *         return new self("Failed to render dashboard: {$dashboard}");
 *     }
 * }
 * ```
 * @example Widget loading failed
 * ```php
 * final class WidgetLoadingException extends DashboardException
 * {
 *     public static function failed(string $widget): self
 *     {
 *         return new self("Failed to load dashboard widget: {$widget}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DashboardException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
