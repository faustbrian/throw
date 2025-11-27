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

/**
 * Base exception for message queue and pub/sub errors.
 *
 * Thrown when message queue operations fail (publish, consume, acknowledge,
 * etc.) or when pub/sub systems encounter errors.
 *
 * @example Message publish failed
 * ```php
 * final class MessagePublishException extends MessageException
 * {
 *     public static function failed(string $topic): self
 *     {
 *         return new self("Failed to publish message to {$topic}");
 *     }
 * }
 * ```
 * @example Consumer error
 * ```php
 * final class MessageConsumerException extends MessageException
 * {
 *     public static function failed(string $queue): self
 *     {
 *         return new self("Failed to consume message from {$queue}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MessageException extends InfrastructureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
