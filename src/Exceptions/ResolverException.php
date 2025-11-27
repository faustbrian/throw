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
 * Base exception for GraphQL resolver failures.
 *
 * Thrown when GraphQL field resolvers fail to execute or return
 * invalid data.
 *
 * @example Resolver execution failed
 * ```php
 * final class ResolverExecutionException extends ResolverException
 * {
 *     public static function failed(string $field): self
 *     {
 *         return new self("Resolver failed for field: {$field}");
 *     }
 * }
 * ```
 * @example Resolver not found
 * ```php
 * final class ResolverNotFoundException extends ResolverException
 * {
 *     public static function forField(string $field): self
 *     {
 *         return new self("No resolver found for field: {$field}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ResolverException extends GraphQLException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
