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
 * Base exception for negotiation process failures.
 *
 * Thrown when negotiation processes fail, negotiation rounds timeout,
 * or negotiation operations encounter errors.
 *
 * @example Negotiation failed
 * ```php
 * final class NegotiationFailedException extends NegotiationException
 * {
 *     public static function failed(string $negotiation, string $reason): self
 *     {
 *         return new self("Negotiation '{$negotiation}' failed: {$reason}");
 *     }
 * }
 * ```
 * @example Negotiation timeout
 * ```php
 * final class NegotiationTimeoutException extends NegotiationException
 * {
 *     public static function detected(string $negotiation, int $timeoutSeconds): self
 *     {
 *         return new self("Negotiation '{$negotiation}' timeout after {$timeoutSeconds} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class NegotiationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
