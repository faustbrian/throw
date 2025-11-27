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
 * Base exception for compensation action failures.
 *
 * Thrown when compensation actions fail, rollback operations encounter errors,
 * or saga compensation fails.
 *
 * @example Compensation failed
 * ```php
 * final class CompensationFailedException extends CompensationException
 * {
 *     public static function forAction(string $action, string $saga): self
 *     {
 *         return new self("Compensation action '{$action}' failed for saga: {$saga}");
 *     }
 * }
 * ```
 * @example Compensation not found
 * ```php
 * final class CompensationNotFoundException extends CompensationException
 * {
 *     public static function detected(string $action): self
 *     {
 *         return new self("Compensation action not found: {$action}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CompensationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
