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
 * Exception for agreement validation errors.
 *
 * Thrown when agreement validation fails, agreement terms are invalid,
 * or agreement operations encounter errors.
 *
 * @example Agreement validation failed
 * ```php
 * final class AgreementValidationException extends AgreementException
 * {
 *     public static function failed(string $agreement, array $errors): self
 *     {
 *         return new self("Agreement validation failed for '{$agreement}': " . json_encode($errors));
 *     }
 * }
 * ```
 * @example Agreement expired
 * ```php
 * final class AgreementExpiredException extends AgreementException
 * {
 *     public static function detected(string $agreement, string $expiryDate): self
 *     {
 *         return new self("Agreement '{$agreement}' expired on: {$expiryDate}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AgreementException extends NegotiationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
