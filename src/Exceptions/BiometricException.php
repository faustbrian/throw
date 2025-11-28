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
 * Base exception for biometric authentication errors.
 *
 * Thrown when biometric authentication fails, biometric sensors fail,
 * or biometric enrollment encounters errors.
 *
 * @example Biometric verification failed
 * ```php
 * final class BiometricVerificationException extends BiometricException
 * {
 *     public static function failed(string $type): self
 *     {
 *         return new self("Biometric verification failed using: {$type}");
 *     }
 * }
 * ```
 * @example Biometric sensor unavailable
 * ```php
 * final class BiometricSensorException extends BiometricException
 * {
 *     public static function unavailable(string $sensor): self
 *     {
 *         return new self("Biometric sensor unavailable: {$sensor}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BiometricException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
