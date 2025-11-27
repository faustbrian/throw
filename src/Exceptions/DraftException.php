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
 * Base exception for draft management errors.
 *
 * Thrown when draft operations fail, drafts cannot be saved,
 * or draft lifecycle management encounters errors.
 *
 * @example Draft save failed
 * ```php
 * final class DraftSaveException extends DraftException
 * {
 *     public static function failed(string $draftId): self
 *     {
 *         return new self("Failed to save draft: {$draftId}");
 *     }
 * }
 * ```
 * @example Draft conflict
 * ```php
 * final class DraftConflictException extends DraftException
 * {
 *     public static function detected(string $contentId, string $user): self
 *     {
 *         return new self("Draft conflict for content '{$contentId}': already being edited by {$user}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DraftException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
