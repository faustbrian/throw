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
 * Base exception for delegation pattern failures.
 *
 * Thrown when delegation operations fail, delegates are not found,
 * or delegation operations encounter errors.
 *
 * @example Delegation failed
 * ```php
 * final class DelegationFailedException extends DelegationException
 * {
 *     public static function failed(string $delegator, string $delegate, string $operation): self
 *     {
 *         return new self("Delegation failed from '{$delegator}' to '{$delegate}' for operation: {$operation}");
 *     }
 * }
 * ```
 * @example Delegate not found
 * ```php
 * final class DelegateNotFoundException extends DelegationException
 * {
 *     public static function detected(string $delegateType): self
 *     {
 *         return new self("Delegate not found for type: {$delegateType}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DelegationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
