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
 * Base exception for content publishing failures.
 *
 * Thrown when content publishing fails, publishing workflows fail,
 * or publishing state transitions encounter errors.
 *
 * @example Publishing failed
 * ```php
 * final class PublishingFailedException extends PublishingException
 * {
 *     public static function forContent(string $contentId): self
 *     {
 *         return new self("Failed to publish content: {$contentId}");
 *     }
 * }
 * ```
 * @example Invalid publishing state
 * ```php
 * final class InvalidPublishingStateException extends PublishingException
 * {
 *     public static function transition(string $from, string $to): self
 *     {
 *         return new self("Invalid publishing state transition from '{$from}' to '{$to}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PublishingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
