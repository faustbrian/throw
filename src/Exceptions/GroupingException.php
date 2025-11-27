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
 * Exception for data grouping errors.
 *
 * Thrown when data grouping fails, grouping keys are invalid,
 * or grouping operations encounter errors.
 *
 * @example Grouping failed
 * ```php
 * final class GroupingFailedException extends GroupingException
 * {
 *     public static function forField(string $field, string $reason): self
 *     {
 *         return new self("Data grouping failed for field '{$field}': {$reason}");
 *     }
 * }
 * ```
 * @example Invalid grouping key
 * ```php
 * final class InvalidGroupingKeyException extends GroupingException
 * {
 *     public static function detected(string $key, string $reason): self
 *     {
 *         return new self("Invalid grouping key '{$key}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class GroupingException extends AggregationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
