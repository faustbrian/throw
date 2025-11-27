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
 * Exception for lookup operation failures.
 *
 * Thrown when lookup operations fail, lookup keys are invalid,
 * or lookup operations encounter errors.
 *
 * @example Lookup failed
 * ```php
 * final class LookupFailedException extends LookupException
 * {
 *     public static function failed(string $key, string $reason): self
 *     {
 *         return new self("Lookup failed for key '{$key}': {$reason}");
 *     }
 * }
 * ```
 * @example Invalid lookup key
 * ```php
 * final class InvalidLookupKeyException extends LookupException
 * {
 *     public static function detected(string $key, string $reason): self
 *     {
 *         return new self("Invalid lookup key '{$key}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LookupException extends RegistryException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
