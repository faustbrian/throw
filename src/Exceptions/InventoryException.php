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
 * Base exception for inventory management errors.
 *
 * Thrown when inventory operations fail, stock is insufficient,
 * or inventory tracking encounters errors.
 *
 * @example Out of stock
 * ```php
 * final class OutOfStockException extends InventoryException
 * {
 *     public static function forProduct(string $product, int $requested, int $available): self
 *     {
 *         return new self("Product '{$product}' out of stock: requested {$requested}, available {$available}");
 *     }
 * }
 * ```
 * @example Inventory reservation failed
 * ```php
 * final class InventoryReservationException extends InventoryException
 * {
 *     public static function failed(string $product, int $quantity): self
 *     {
 *         return new self("Failed to reserve {$quantity} units of product: {$product}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InventoryException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
