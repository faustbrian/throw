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
 * Base exception for graph structure errors.
 *
 * Thrown when graph operations fail, graph structure is invalid,
 * or graph traversal encounters errors.
 *
 * @example Graph operation failed
 * ```php
 * final class GraphOperationException extends GraphException
 * {
 *     public static function failed(string $operation, string $graph): self
 *     {
 *         return new self("Graph operation '{$operation}' failed for graph: {$graph}");
 *     }
 * }
 * ```
 * @example Cyclic graph detected
 * ```php
 * final class CyclicGraphException extends GraphException
 * {
 *     public static function detected(string $graph, array $cycle): self
 *     {
 *         return new self("Cyclic graph detected in '{$graph}': " . implode(' -> ', $cycle));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class GraphException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
