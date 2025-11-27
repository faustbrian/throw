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
 * Base exception for Stripe-specific errors.
 *
 * Thrown when Stripe API calls fail or Stripe-specific operations
 * encounter errors. Extends PaymentException for Stripe failures.
 *
 * @example Stripe API error
 * ```php
 * final class StripeApiException extends StripeException
 * {
 *     public static function requestFailed(string $error): self
 *     {
 *         return new self("Stripe API error: {$error}");
 *     }
 * }
 * ```
 * @example Webhook verification failed
 * ```php
 * final class StripeWebhookException extends StripeException
 * {
 *     public static function invalidSignature(): self
 *     {
 *         return new self('Stripe webhook signature verification failed');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class StripeException extends PaymentException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
