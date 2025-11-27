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
 * Base exception for search operation failures.
 *
 * Thrown when search operations fail, search engines are unavailable,
 * or search queries encounter errors.
 *
 * @example Search failed
 * ```php
 * final class SearchFailedException extends SearchException
 * {
 *     public static function forQuery(string $query): self
 *     {
 *         return new self("Search failed for query: {$query}");
 *     }
 * }
 * ```
 * @example Search engine unavailable
 * ```php
 * final class SearchEngineUnavailableException extends SearchException
 * {
 *     public static function detected(string $engine): self
 *     {
 *         return new self("Search engine unavailable: {$engine}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SearchException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
