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

/**
 * Base exception for email sending errors.
 *
 * Thrown when email operations fail (send, queue, template rendering, etc.).
 * Extends NotificationException for email-specific failures.
 *
 * @example Email send failed
 * ```php
 * final class EmailSendException extends EmailException
 * {
 *     public static function failed(string $to): self
 *     {
 *         return new self("Failed to send email to {$to}");
 *     }
 * }
 * ```
 * @example Invalid email address
 * ```php
 * final class InvalidEmailAddressException extends EmailException
 * {
 *     public static function format(string $email): self
 *     {
 *         return new self("Invalid email address: {$email}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class EmailException extends NotificationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
