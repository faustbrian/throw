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
 * Base exception for data filtering failures.
 *
 * Thrown when data filtering fails, filter criteria are invalid,
 * or filter operations encounter errors.
 *
 * @example Filter application failed
 * ```php
 * final class FilterApplicationException extends FilterException
 * {
 *     public static function failed(string $filter, string $field): self
 *     {
 *         return new self("Filter '{$filter}' application failed for field: {$field}");
 *     }
 * }
 * ```
 * @example Invalid filter criteria
 * ```php
 * final class InvalidFilterCriteriaException extends FilterException
 * {
 *     public static function detected(string $field, mixed $criteria): self
 *     {
 *         return new self("Invalid filter criteria for field '{$field}': " . json_encode($criteria));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FilterException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
