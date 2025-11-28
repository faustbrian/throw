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
 * Base exception for content revision errors.
 *
 * Thrown when content revision operations fail, revisions cannot be created,
 * or revision history encounters errors.
 *
 * @example Revision not found
 * ```php
 * final class RevisionNotFoundException extends RevisionException
 * {
 *     public static function forId(int $revisionId): self
 *     {
 *         return new self("Content revision not found: {$revisionId}");
 *     }
 * }
 * ```
 * @example Cannot restore revision
 * ```php
 * final class RevisionRestoreException extends RevisionException
 * {
 *     public static function failed(int $revisionId): self
 *     {
 *         return new self("Failed to restore content revision: {$revisionId}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RevisionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
