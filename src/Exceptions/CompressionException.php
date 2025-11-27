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
 * Base exception for data compression errors.
 *
 * Thrown when data compression fails, decompression fails,
 * or compression algorithms are not supported.
 *
 * @example Compression failed
 * ```php
 * final class CompressionFailedException extends CompressionException
 * {
 *     public static function forAlgorithm(string $algorithm): self
 *     {
 *         return new self("Compression failed using algorithm: {$algorithm}");
 *     }
 * }
 * ```
 * @example Decompression failed
 * ```php
 * final class DecompressionFailedException extends CompressionException
 * {
 *     public static function corruptedData(): self
 *     {
 *         return new self('Failed to decompress corrupted data');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CompressionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
