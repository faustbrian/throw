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
 * Exception for tree traversal failures.
 *
 * Thrown when tree traversal fails, tree structure is invalid,
 * or tree operations encounter errors.
 *
 * @example Tree traversal failed
 * ```php
 * final class TreeTraversalException extends TreeException
 * {
 *     public static function failed(string $strategy, string $tree): self
 *     {
 *         return new self("Tree traversal using '{$strategy}' failed for tree: {$tree}");
 *     }
 * }
 * ```
 * @example Invalid tree structure
 * ```php
 * final class InvalidTreeStructureException extends TreeException
 * {
 *     public static function detected(string $tree, string $issue): self
 *     {
 *         return new self("Invalid tree structure for '{$tree}': {$issue}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TreeException extends GraphException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
