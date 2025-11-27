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
 * Exception for bulk operation errors.
 *
 * Thrown when bulk operations fail, partial bulk failures occur,
 * or bulk validation encounters errors.
 *
 * @example Bulk operation failed
 * ```php
 * final class BulkOperationFailedException extends BulkException
 * {
 *     public static function forOperation(string $operation, int $total, int $failed): self
 *     {
 *         return new self("Bulk '{$operation}' failed: {$failed}/{$total} items");
 *     }
 * }
 * ```
 * @example Partial bulk failure
 * ```php
 * final class PartialBulkFailureException extends BulkException
 * {
 *     public static function detected(int $succeeded, int $failed, array $errors): self
 *     {
 *         return new self("Partial bulk failure: {$succeeded} succeeded, {$failed} failed");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BulkException extends BatchException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
