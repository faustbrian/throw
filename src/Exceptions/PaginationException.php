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
 * Base exception for pagination operation errors.
 *
 * Thrown when pagination fails, page numbers are invalid,
 * or pagination operations encounter errors.
 *
 * @example Pagination failed
 * ```php
 * final class PaginationFailedException extends PaginationException
 * {
 *     public static function forQuery(string $query): self
 *     {
 *         return new self("Pagination failed for query: {$query}");
 *     }
 * }
 * ```
 * @example Invalid page number
 * ```php
 * final class InvalidPageNumberException extends PaginationException
 * {
 *     public static function detected(int $page, int $maxPage): self
 *     {
 *         return new self("Invalid page number {$page}: maximum page is {$maxPage}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PaginationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
