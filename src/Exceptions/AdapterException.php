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
 * Base exception for channel adapter failures.
 *
 * Thrown when channel adapters fail, adapter initialization fails,
 * or adapter operations encounter errors.
 *
 * @example Adapter not found
 * ```php
 * final class AdapterNotFoundException extends AdapterException
 * {
 *     public static function forType(string $type): self
 *     {
 *         return new self("Channel adapter not found for type: {$type}");
 *     }
 * }
 * ```
 * @example Adapter initialization failed
 * ```php
 * final class AdapterInitializationException extends AdapterException
 * {
 *     public static function failed(string $adapter): self
 *     {
 *         return new self("Failed to initialize adapter: {$adapter}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AdapterException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
