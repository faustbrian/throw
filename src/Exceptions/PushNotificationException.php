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
 * Base exception for push notification errors.
 *
 * Thrown when push notifications fail, device tokens are invalid,
 * or notification service operations encounter errors.
 *
 * @example Push failed
 * ```php
 * final class PushNotificationFailedException extends PushNotificationException
 * {
 *     public static function toDevice(string $deviceToken): self
 *     {
 *         return new self("Failed to send push notification to device: {$deviceToken}");
 *     }
 * }
 * ```
 * @example Invalid device token
 * ```php
 * final class InvalidDeviceTokenException extends PushNotificationException
 * {
 *     public static function expired(string $token): self
 *     {
 *         return new self("Device token expired or invalid: {$token}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PushNotificationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
