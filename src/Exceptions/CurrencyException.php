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
 * Base exception for currency conversion failures.
 *
 * Thrown when currency operations fail, exchange rates are unavailable,
 * or currency codes are invalid.
 *
 * @example Invalid currency code
 * ```php
 * final class InvalidCurrencyCodeException extends CurrencyException
 * {
 *     public static function forCode(string $code): self
 *     {
 *         return new self("Invalid currency code: {$code}");
 *     }
 * }
 * ```
 * @example Exchange rate unavailable
 * ```php
 * final class ExchangeRateUnavailableException extends CurrencyException
 * {
 *     public static function between(string $from, string $to): self
 *     {
 *         return new self("Exchange rate unavailable from {$from} to {$to}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CurrencyException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
