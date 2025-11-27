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
 * Base exception for billing and subscription errors.
 *
 * Thrown when billing operations fail, subscriptions cannot be created
 * or updated, or invoice generation fails.
 *
 * @example Subscription creation failed
 * ```php
 * final class SubscriptionCreationException extends BillingException
 * {
 *     public static function failed(string $plan): self
 *     {
 *         return new self("Failed to create subscription for plan: {$plan}");
 *     }
 * }
 * ```
 * @example Invoice generation failed
 * ```php
 * final class InvoiceGenerationException extends BillingException
 * {
 *     public static function failed(): self
 *     {
 *         return new self('Failed to generate invoice');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BillingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
