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
 * Exception for node operation errors.
 *
 * Thrown when node operations fail, nodes are not found,
 * or node manipulation encounters errors.
 *
 * @example Node operation failed
 * ```php
 * final class NodeOperationException extends NodeException
 * {
 *     public static function failed(string $operation, string $nodeId): self
 *     {
 *         return new self("Node operation '{$operation}' failed for node: {$nodeId}");
 *     }
 * }
 * ```
 * @example Node not found
 * ```php
 * final class NodeNotFoundException extends NodeException
 * {
 *     public static function detected(string $nodeId, string $tree): self
 *     {
 *         return new self("Node '{$nodeId}' not found in tree: {$tree}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class NodeException extends TreeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
