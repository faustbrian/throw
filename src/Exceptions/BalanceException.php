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
 * Exception for balance calculation errors.
 *
 * Thrown when balance calculation fails, balance is insufficient,
 * or balance operations encounter errors.
 *
 * @example Balance calculation failed
 * ```php
 * final class BalanceCalculationException extends BalanceException
 * {
 *     public static function failed(string $account, string $reason): self
 *     {
 *         return new self("Balance calculation failed for account '{$account}': {$reason}");
 *     }
 * }
 * ```
 * @example Insufficient balance
 * ```php
 * final class InsufficientBalanceException extends BalanceException
 * {
 *     public static function detected(string $account, float $required, float $available): self
 *     {
 *         return new self("Insufficient balance for account '{$account}': required={$required}, available={$available}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BalanceException extends ReconciliationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
