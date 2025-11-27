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
 * Exception for index operation errors.
 *
 * Thrown when indexing operations fail, index creation encounters errors,
 * or index maintenance operations fail.
 *
 * @example Index operation failed
 * ```php
 * final class IndexOperationException extends IndexException
 * {
 *     public static function failed(string $operation, string $index): self
 *     {
 *         return new self("Index operation '{$operation}' failed for index: {$index}");
 *     }
 * }
 * ```
 * @example Index not found
 * ```php
 * final class IndexNotFoundException extends IndexException
 * {
 *     public static function detected(string $index): self
 *     {
 *         return new self("Index not found: {$index}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class IndexException extends SearchException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
