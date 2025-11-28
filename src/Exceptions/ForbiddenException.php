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
 * Base exception for authorization failures.
 *
 * Thrown when a user is authenticated but lacks permission to perform
 * the requested action. Maps to HTTP 403 Forbidden.
 *
 * @example Missing permission
 * ```php
 * final class MissingPermissionException extends ForbiddenException
 * {
 *     public static function forAction(string $action): self
 *     {
 *         return new self("Missing permission: {$action}");
 *     }
 * }
 * ```
 * @example Insufficient role
 * ```php
 * final class InsufficientRoleException extends ForbiddenException
 * {
 *     public static function requiresRole(string $role): self
 *     {
 *         return new self("Action requires {$role} role");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ForbiddenException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
