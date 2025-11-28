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
 * Base exception for ledger operation errors.
 *
 * Thrown when ledger operations fail, ledger entries are invalid,
 * or ledger operations encounter errors.
 *
 * @example Ledger operation failed
 * ```php
 * final class LedgerOperationException extends LedgerException
 * {
 *     public static function failed(string $operation, string $ledger): self
 *     {
 *         return new self("Ledger operation '{$operation}' failed for ledger: {$ledger}");
 *     }
 * }
 * ```
 * @example Invalid ledger entry
 * ```php
 * final class InvalidLedgerEntryException extends LedgerException
 * {
 *     public static function detected(string $entryId, string $reason): self
 *     {
 *         return new self("Invalid ledger entry '{$entryId}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LedgerException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
