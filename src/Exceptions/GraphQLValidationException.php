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
 * Base exception for GraphQL schema validation errors.
 *
 * Thrown when GraphQL schema validation fails or input validation
 * errors occur in GraphQL operations.
 *
 * @example Schema validation failed
 * ```php
 * final class SchemaValidationException extends GraphQLValidationException
 * {
 *     public static function invalid(string $field): self
 *     {
 *         return new self("Schema validation failed for field: {$field}");
 *     }
 * }
 * ```
 * @example Input validation error
 * ```php
 * final class GraphQLInputException extends GraphQLValidationException
 * {
 *     public static function invalid(string $input): self
 *     {
 *         return new self("Invalid GraphQL input: {$input}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class GraphQLValidationException extends GraphQLException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
