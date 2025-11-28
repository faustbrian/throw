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
 * Base exception for webhook delivery failures.
 *
 * Thrown when webhook delivery fails, webhook endpoints are unreachable,
 * or webhook operations encounter errors.
 *
 * @example Webhook delivery failed
 * ```php
 * final class WebhookDeliveryException extends WebhookException
 * {
 *     public static function failed(string $url, int $statusCode): self
 *     {
 *         return new self("Webhook delivery failed to '{$url}': HTTP {$statusCode}");
 *     }
 * }
 * ```
 * @example Webhook timeout
 * ```php
 * final class WebhookTimeoutException extends WebhookException
 * {
 *     public static function detected(string $url, int $timeout): self
 *     {
 *         return new self("Webhook timeout after {$timeout}s for URL: {$url}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class WebhookException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
