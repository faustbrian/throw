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
 * Exception for materialized view errors.
 *
 * Thrown when materialized view operations fail, view creation encounters errors,
 * or view maintenance fails.
 *
 * @example Materialized view creation failed
 * ```php
 * final class MaterializedViewCreationException extends MaterializedViewException
 * {
 *     public static function failed(string $view, string $reason): self
 *     {
 *         return new self("Materialized view '{$view}' creation failed: {$reason}");
 *     }
 * }
 * ```
 * @example View out of sync
 * ```php
 * final class ViewOutOfSyncException extends MaterializedViewException
 * {
 *     public static function detected(string $view, int $lagSeconds): self
 *     {
 *         return new self("Materialized view '{$view}' out of sync by {$lagSeconds} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MaterializedViewException extends ProjectionException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
