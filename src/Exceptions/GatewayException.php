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
 * Base exception for API gateway errors.
 *
 * Thrown when API gateway operations fail, gateway routing encounters errors,
 * or gateway operations fail.
 *
 * @example Gateway operation failed
 * ```php
 * final class GatewayOperationException extends GatewayException
 * {
 *     public static function failed(string $operation, string $gateway): self
 *     {
 *         return new self("Gateway operation '{$operation}' failed for gateway: {$gateway}");
 *     }
 * }
 * ```
 * @example Gateway timeout
 * ```php
 * final class GatewayTimeoutException extends GatewayException
 * {
 *     public static function detected(string $gateway, int $timeoutMs): self
 *     {
 *         return new self("Gateway '{$gateway}' timeout after {$timeoutMs}ms");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class GatewayException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
