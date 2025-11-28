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
 * Exception for log handler errors.
 *
 * Thrown when log handlers fail, handler operations encounter errors,
 * or handler configuration is invalid.
 *
 * @example Handler operation failed
 * ```php
 * final class HandlerOperationException extends HandlerException
 * {
 *     public static function failed(string $handler, string $operation): self
 *     {
 *         return new self("Handler '{$handler}' operation failed: {$operation}");
 *     }
 * }
 * ```
 * @example Handler not available
 * ```php
 * final class HandlerUnavailableException extends HandlerException
 * {
 *     public static function detected(string $handler): self
 *     {
 *         return new self("Log handler unavailable: {$handler}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class HandlerException extends LoggerException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
