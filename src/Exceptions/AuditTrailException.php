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
 * Exception for audit trail recording failures.
 *
 * Thrown when audit trail recording fails, audit entries are invalid,
 * or audit trail operations encounter errors.
 *
 * @example Audit trail recording failed
 * ```php
 * final class AuditTrailRecordingException extends AuditTrailException
 * {
 *     public static function failed(string $event, string $reason): self
 *     {
 *         return new self("Audit trail recording failed for event '{$event}': {$reason}");
 *     }
 * }
 * ```
 * @example Audit entry missing
 * ```php
 * final class AuditEntryMissingException extends AuditTrailException
 * {
 *     public static function detected(string $entryId): self
 *     {
 *         return new self("Audit entry missing: {$entryId}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AuditTrailException extends LedgerException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
