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
 * Exception for settlement operation failures.
 *
 * Thrown when settlement operations fail, settlement processing encounters errors,
 * or settlement operations fail.
 *
 * @example Settlement failed
 * ```php
 * final class SettlementFailedException extends SettlementException
 * {
 *     public static function failed(string $transaction, string $reason): self
 *     {
 *         return new self("Settlement failed for transaction '{$transaction}': {$reason}");
 *     }
 * }
 * ```
 * @example Settlement timeout
 * ```php
 * final class SettlementTimeoutException extends SettlementException
 * {
 *     public static function detected(string $transaction, int $timeoutSeconds): self
 *     {
 *         return new self("Settlement timeout for transaction '{$transaction}' after {$timeoutSeconds} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SettlementException extends ReconciliationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
