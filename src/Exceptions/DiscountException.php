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
 * Base exception for discount application failures.
 *
 * Thrown when discount application fails, discount codes are invalid,
 * or discount rules cannot be applied.
 *
 * @example Invalid discount code
 * ```php
 * final class InvalidDiscountCodeException extends DiscountException
 * {
 *     public static function notFound(string $code): self
 *     {
 *         return new self("Invalid or expired discount code: {$code}");
 *     }
 * }
 * ```
 * @example Discount not applicable
 * ```php
 * final class DiscountNotApplicableException extends DiscountException
 * {
 *     public static function forProduct(string $code, string $product): self
 *     {
 *         return new self("Discount code '{$code}' not applicable to product: {$product}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DiscountException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
