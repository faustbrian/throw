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
 * Base exception for regulatory requirement violations.
 *
 * Thrown when regulatory requirements are violated, regulatory checks fail,
 * or regulatory operations encounter errors.
 *
 * @example SOC 2 violation
 * ```php
 * final class Soc2ViolationException extends RegulationException
 * {
 *     public static function detected(string $control): self
 *     {
 *         return new self("SOC 2 control violation: {$control}");
 *     }
 * }
 * ```
 * @example HIPAA violation
 * ```php
 * final class HipaaViolationException extends RegulationException
 * {
 *     public static function unauthorizedAccess(string $resource): self
 *     {
 *         return new self("HIPAA violation: unauthorized access to {$resource}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RegulationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
