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
 * Base exception for factory creation failures.
 *
 * Thrown when factory creation fails, factory methods encounter errors,
 * or product instantiation fails.
 *
 * @example Factory creation failed
 * ```php
 * final class FactoryCreationException extends FactoryException
 * {
 *     public static function failed(string $factory, string $product): self
 *     {
 *         return new self("Factory '{$factory}' failed to create product: {$product}");
 *     }
 * }
 * ```
 * @example Unknown product type
 * ```php
 * final class UnknownProductTypeException extends FactoryException
 * {
 *     public static function detected(string $type, array $supportedTypes): self
 *     {
 *         return new self("Unknown product type '{$type}': supported types are " . implode(', ', $supportedTypes));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FactoryException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
