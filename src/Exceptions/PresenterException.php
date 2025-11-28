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
 * Base exception for presenter pattern errors.
 *
 * Thrown when presenter initialization fails, presenter methods are invalid,
 * or presenter operations encounter errors.
 *
 * @example Presenter initialization failed
 * ```php
 * final class PresenterInitializationException extends PresenterException
 * {
 *     public static function failed(string $presenter, string $reason): self
 *     {
 *         return new self("Presenter '{$presenter}' initialization failed: {$reason}");
 *     }
 * }
 * ```
 * @example Presenter method not found
 * ```php
 * final class PresenterMethodNotFoundException extends PresenterException
 * {
 *     public static function detected(string $presenter, string $method): self
 *     {
 *         return new self("Presenter '{$presenter}' method not found: {$method}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PresenterException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
