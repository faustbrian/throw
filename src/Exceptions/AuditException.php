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
 * Base exception for audit logging failures.
 *
 * Thrown when audit logging fails, audit records cannot be created,
 * or audit trail operations encounter errors.
 *
 * @example Audit logging failed
 * ```php
 * final class AuditLogFailedException extends AuditException
 * {
 *     public static function forAction(string $action, string $user): self
 *     {
 *         return new self("Failed to log audit for action '{$action}' by user: {$user}");
 *     }
 * }
 * ```
 * @example Audit trail incomplete
 * ```php
 * final class IncompleteAuditTrailException extends AuditException
 * {
 *     public static function forEntity(string $entity): self
 *     {
 *         return new self("Incomplete audit trail for entity: {$entity}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AuditException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
