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
 * Exception for stream producer errors.
 *
 * Thrown when stream producers fail, message publishing fails,
 * or producer operations encounter errors.
 *
 * @example Producer failed
 * ```php
 * final class ProducerFailedException extends ProducerException
 * {
 *     public static function forTopic(string $topic): self
 *     {
 *         return new self("Producer failed for topic: {$topic}");
 *     }
 * }
 * ```
 * @example Message publishing failed
 * ```php
 * final class MessagePublishException extends ProducerException
 * {
 *     public static function failed(string $topic, string $key): self
 *     {
 *         return new self("Failed to publish message to topic '{$topic}' with key: {$key}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ProducerException extends StreamingException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
