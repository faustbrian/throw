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
 * Base exception for third-party service failures.
 *
 * Thrown when external services (payment gateways, email providers,
 * SMS services, etc.) fail or return errors. Specialized InfrastructureException.
 *
 * @example Payment gateway error
 * ```php
 * final class PaymentGatewayException extends ExternalServiceException
 * {
 *     public static function declined(string $reason): self
 *     {
 *         return new self("Payment declined: {$reason}");
 *     }
 * }
 * ```
 * @example Email service error
 * ```php
 * final class EmailServiceException extends ExternalServiceException
 * {
 *     public static function sendFailed(): self
 *     {
 *         return new self('Failed to send email via provider');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ExternalServiceException extends InfrastructureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
