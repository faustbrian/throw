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
 * Base exception for Slack integration failures.
 *
 * Thrown when Slack API calls fail, webhooks fail,
 * or Slack message delivery encounters errors.
 *
 * @example Message delivery failed
 * ```php
 * final class SlackMessageFailedException extends SlackException
 * {
 *     public static function toChannel(string $channel): self
 *     {
 *         return new self("Failed to send Slack message to channel: {$channel}");
 *     }
 * }
 * ```
 * @example Invalid webhook
 * ```php
 * final class InvalidSlackWebhookException extends SlackException
 * {
 *     public static function url(string $url): self
 *     {
 *         return new self("Invalid Slack webhook URL: {$url}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SlackException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
