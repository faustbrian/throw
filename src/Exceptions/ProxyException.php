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
 * Base exception for proxy operation failures.
 *
 * Thrown when proxy operations fail, proxy connections are refused,
 * or proxy forwarding encounters errors.
 *
 * @example Proxy operation failed
 * ```php
 * final class ProxyOperationException extends ProxyException
 * {
 *     public static function failed(string $proxy, string $operation): self
 *     {
 *         return new self("Proxy '{$proxy}' operation '{$operation}' failed");
 *     }
 * }
 * ```
 * @example Proxy connection refused
 * ```php
 * final class ProxyConnectionRefusedException extends ProxyException
 * {
 *     public static function detected(string $proxy, string $target): self
 *     {
 *         return new self("Proxy '{$proxy}' connection refused to target: {$target}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ProxyException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
