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
 * Exception for stream consumer failures.
 *
 * Thrown when stream consumers fail, consumer groups fail,
 * or message consumption encounters errors.
 *
 * @example Consumer failed
 * ```php
 * final class ConsumerFailedException extends ConsumerException
 * {
 *     public static function forTopic(string $topic): self
 *     {
 *         return new self("Consumer failed for topic: {$topic}");
 *     }
 * }
 * ```
 * @example Consumer lag exceeded
 * ```php
 * final class ConsumerLagException extends ConsumerException
 * {
 *     public static function detected(string $consumer, int $lag): self
 *     {
 *         return new self("Consumer '{$consumer}' lag exceeded threshold: {$lag} messages");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConsumerException extends StreamingException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
