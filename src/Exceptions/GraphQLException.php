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
 * Base exception for GraphQL query and mutation errors.
 *
 * Thrown when GraphQL operations fail, queries are malformed,
 * or mutations encounter errors.
 *
 * @example Query execution failed
 * ```php
 * final class GraphQLQueryException extends GraphQLException
 * {
 *     public static function failed(string $query): self
 *     {
 *         return new self("GraphQL query failed: {$query}");
 *     }
 * }
 * ```
 * @example Mutation error
 * ```php
 * final class GraphQLMutationException extends GraphQLException
 * {
 *     public static function failed(string $mutation): self
 *     {
 *         return new self("GraphQL mutation failed: {$mutation}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class GraphQLException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
