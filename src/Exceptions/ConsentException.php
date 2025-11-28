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
 * Exception for consent verification failures.
 *
 * Thrown when consent verification fails, consent is missing,
 * or consent operations encounter errors.
 *
 * @example Consent verification failed
 * ```php
 * final class ConsentVerificationException extends ConsentException
 * {
 *     public static function failed(string $user, string $operation): self
 *     {
 *         return new self("Consent verification failed for user '{$user}' on operation: {$operation}");
 *     }
 * }
 * ```
 * @example Consent not given
 * ```php
 * final class ConsentNotGivenException extends ConsentException
 * {
 *     public static function detected(string $user, string $permission): self
 *     {
 *         return new self("Consent not given by user '{$user}' for permission: {$permission}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConsentException extends AgreementException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
