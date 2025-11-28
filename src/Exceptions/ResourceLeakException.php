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
 * Exception thrown when resource leaks are detected.
 *
 * Thrown when resources (file handles, database connections, memory) are not
 * properly released, leading to potential memory leaks or resource exhaustion.
 *
 * @example Unclosed resource
 * ```php
 * final class UnclosedResourceException extends ResourceLeakException
 * {
 *     public static function detected(string $resourceType, int $count): self
 *     {
 *         return new self("Detected {$count} unclosed {$resourceType} resource(s)");
 *     }
 * }
 * ```
 * @example Connection leak
 * ```php
 * final class ConnectionLeakException extends ResourceLeakException
 * {
 *     public static function inPool(string $pool, int $leaked): self
 *     {
 *         return new self("Connection pool '{$pool}' has {$leaked} leaked connection(s)");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ResourceLeakException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
