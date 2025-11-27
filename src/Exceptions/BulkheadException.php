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
 * Base exception for bulkhead isolation failures.
 *
 * Thrown when bulkhead capacity is exceeded, isolation fails,
 * or bulkhead operations encounter errors.
 *
 * @example Bulkhead full
 * ```php
 * final class BulkheadFullException extends BulkheadException
 * {
 *     public static function detected(string $bulkhead, int $capacity): self
 *     {
 *         return new self("Bulkhead '{$bulkhead}' is full: capacity={$capacity}");
 *     }
 * }
 * ```
 * @example Bulkhead rejected
 * ```php
 * final class BulkheadRejectedException extends BulkheadException
 * {
 *     public static function detected(string $bulkhead, string $reason): self
 *     {
 *         return new self("Bulkhead '{$bulkhead}' rejected request: {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BulkheadException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
