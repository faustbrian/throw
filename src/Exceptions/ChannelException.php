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
 * Base exception for communication channel errors.
 *
 * Thrown when communication channel operations fail, channels are unavailable,
 * or channel configuration encounters errors.
 *
 * @example Channel unavailable
 * ```php
 * final class ChannelUnavailableException extends ChannelException
 * {
 *     public static function forName(string $channel): self
 *     {
 *         return new self("Communication channel unavailable: {$channel}");
 *     }
 * }
 * ```
 * @example Invalid channel configuration
 * ```php
 * final class InvalidChannelConfigException extends ChannelException
 * {
 *     public static function detected(string $channel, string $parameter): self
 *     {
 *         return new self("Invalid configuration for channel '{$channel}': {$parameter}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ChannelException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
