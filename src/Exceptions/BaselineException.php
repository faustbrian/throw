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
 * Exception for baseline comparison errors.
 *
 * Thrown when baseline comparison fails, baseline data is unavailable,
 * or baseline operations encounter errors.
 *
 * @example Baseline comparison failed
 * ```php
 * final class BaselineComparisonException extends BaselineException
 * {
 *     public static function failed(string $metric, string $reason): self
 *     {
 *         return new self("Baseline comparison failed for metric '{$metric}': {$reason}");
 *     }
 * }
 * ```
 * @example Baseline not found
 * ```php
 * final class BaselineNotFoundException extends BaselineException
 * {
 *     public static function detected(string $baseline): self
 *     {
 *         return new self("Baseline not found: {$baseline}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BaselineException extends ProfileException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
