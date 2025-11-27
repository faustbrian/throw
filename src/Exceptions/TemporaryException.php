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
 * Base exception for temporary/transient failures.
 *
 * Thrown for errors that are likely temporary and may succeed if
 * retried. Helps distinguish transient from permanent failures.
 *
 * @example Service temporarily unavailable
 * ```php
 * final class ServiceUnavailableException extends TemporaryException
 * {
 *     public static function maintenance(): self
 *     {
 *         return new self('Service temporarily unavailable for maintenance');
 *     }
 * }
 * ```
 * @example Temporary network issue
 * ```php
 * final class TransientNetworkException extends TemporaryException
 * {
 *     public static function detected(): self
 *     {
 *         return new self('Temporary network issue, please retry');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TemporaryException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
