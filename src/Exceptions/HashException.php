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
 * Base exception for hashing operation failures.
 *
 * Thrown when hashing operations fail, hash verification fails,
 * or hash algorithms are not supported.
 *
 * @example Hash verification failed
 * ```php
 * final class HashMismatchException extends HashException
 * {
 *     public static function forData(string $expected, string $actual): self
 *     {
 *         return new self("Hash mismatch: expected {$expected}, got {$actual}");
 *     }
 * }
 * ```
 * @example Unsupported algorithm
 * ```php
 * final class UnsupportedHashAlgorithmException extends HashException
 * {
 *     public static function forAlgorithm(string $algorithm): self
 *     {
 *         return new self("Unsupported hash algorithm: {$algorithm}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class HashException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
