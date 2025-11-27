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
 * Exception for query parsing failures.
 *
 * Thrown when query parsing fails, query syntax is invalid,
 * or query execution encounters errors.
 *
 * @example Query parsing failed
 * ```php
 * final class QueryParsingException extends QueryException
 * {
 *     public static function failed(string $query, string $reason): self
 *     {
 *         return new self("Query parsing failed for '{$query}': {$reason}");
 *     }
 * }
 * ```
 * @example Invalid query syntax
 * ```php
 * final class InvalidQuerySyntaxException extends QueryException
 * {
 *     public static function detected(string $query, int $position): self
 *     {
 *         return new self("Invalid query syntax at position {$position}: {$query}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class QueryException extends SearchException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
