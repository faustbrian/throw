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
 * Base exception for network interface failures.
 *
 * Thrown when network interfaces fail, interfaces are down,
 * or interface configuration encounters errors.
 *
 * @example Interface down
 * ```php
 * final class NetworkInterfaceDownException extends NetworkInterfaceException
 * {
 *     public static function forInterface(string $interface): self
 *     {
 *         return new self("Network interface is down: {$interface}");
 *     }
 * }
 * ```
 * @example Interface not found
 * ```php
 * final class NetworkInterfaceNotFoundException extends NetworkInterfaceException
 * {
 *     public static function forName(string $name): self
 *     {
 *         return new self("Network interface not found: {$name}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class NetworkInterfaceException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
