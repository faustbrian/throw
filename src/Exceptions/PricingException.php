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
 * Base exception for pricing calculation errors.
 *
 * Thrown when pricing calculations fail, price rules are invalid,
 * or pricing data is missing or corrupted.
 *
 * @example Price not found
 * ```php
 * final class PriceNotFoundException extends PricingException
 * {
 *     public static function forProduct(string $product, string $currency): self
 *     {
 *         return new self("Price not found for product '{$product}' in currency: {$currency}");
 *     }
 * }
 * ```
 * @example Invalid price
 * ```php
 * final class InvalidPriceException extends PricingException
 * {
 *     public static function negative(string $product): self
 *     {
 *         return new self("Invalid negative price for product: {$product}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PricingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
