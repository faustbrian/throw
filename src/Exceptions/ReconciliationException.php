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
 * Base exception for reconciliation process errors.
 *
 * Thrown when reconciliation processes fail, discrepancies are found,
 * or reconciliation operations encounter errors.
 *
 * @example Reconciliation failed
 * ```php
 * final class ReconciliationFailedException extends ReconciliationException
 * {
 *     public static function failed(string $account, string $period): self
 *     {
 *         return new self("Reconciliation failed for account '{$account}' in period: {$period}");
 *     }
 * }
 * ```
 * @example Discrepancy detected
 * ```php
 * final class DiscrepancyDetectedException extends ReconciliationException
 * {
 *     public static function found(string $account, float $expected, float $actual): self
 *     {
 *         return new self("Discrepancy detected for account '{$account}': expected={$expected}, actual={$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ReconciliationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
