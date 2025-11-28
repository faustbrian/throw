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
 * Base exception for sorting operation errors.
 *
 * Thrown when sorting operations fail, sort criteria are invalid,
 * or sort comparisons encounter errors.
 *
 * @example Sorting failed
 * ```php
 * final class SortingFailedException extends SortException
 * {
 *     public static function forField(string $field): self
 *     {
 *         return new self("Sorting failed for field: {$field}");
 *     }
 * }
 * ```
 * @example Invalid sort criteria
 * ```php
 * final class InvalidSortCriteriaException extends SortException
 * {
 *     public static function detected(string $field, string $direction): self
 *     {
 *         return new self("Invalid sort criteria for field '{$field}': direction must be 'asc' or 'desc', got '{$direction}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SortException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
