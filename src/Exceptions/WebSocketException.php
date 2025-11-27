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
 * Base exception for WebSocket connection and message errors.
 *
 * Thrown when WebSocket connections fail, messages cannot be sent,
 * or connection state is invalid.
 *
 * @example Connection failed
 * ```php
 * final class WebSocketConnectionException extends WebSocketException
 * {
 *     public static function failed(string $url): self
 *     {
 *         return new self("WebSocket connection failed: {$url}");
 *     }
 * }
 * ```
 * @example Message send failed
 * ```php
 * final class WebSocketMessageException extends WebSocketException
 * {
 *     public static function sendFailed(): self
 *     {
 *         return new self('Failed to send WebSocket message');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class WebSocketException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
