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
 * Base exception for encryption and decryption failures.
 *
 * Thrown when encryption operations fail, decryption fails,
 * or cryptographic keys are invalid.
 *
 * @example Decryption failed
 * ```php
 * final class DecryptionFailedException extends EncryptionException
 * {
 *     public static function invalidPayload(): self
 *     {
 *         return new self('Could not decrypt the payload');
 *     }
 * }
 * ```
 * @example Invalid key
 * ```php
 * final class InvalidEncryptionKeyException extends EncryptionException
 * {
 *     public static function tooShort(int $length, int $required): self
 *     {
 *         return new self("Encryption key length {$length} is less than required {$required}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class EncryptionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
