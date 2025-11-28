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
 * Base exception for SMS delivery failures.
 *
 * Thrown when SMS sending fails, phone numbers are invalid,
 * or SMS provider operations encounter errors.
 *
 * @example SMS delivery failed
 * ```php
 * final class SmsDeliveryException extends SmsException
 * {
 *     public static function failed(string $recipient): self
 *     {
 *         return new self("Failed to deliver SMS to: {$recipient}");
 *     }
 * }
 * ```
 * @example Invalid phone number
 * ```php
 * final class InvalidPhoneNumberException extends SmsException
 * {
 *     public static function format(string $number): self
 *     {
 *         return new self("Invalid phone number format: {$number}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SmsException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
