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
 * Base exception for data corruption detection.
 *
 * Thrown when data corruption is detected, files are corrupted,
 * or data structures are invalid due to corruption.
 *
 * @example File corruption
 * ```php
 * final class FileCorruptionException extends CorruptionException
 * {
 *     public static function detected(string $file): self
 *     {
 *         return new self("File corruption detected: {$file}");
 *     }
 * }
 * ```
 * @example Memory corruption
 * ```php
 * final class MemoryCorruptionException extends CorruptionException
 * {
 *     public static function detected(string $region): self
 *     {
 *         return new self("Memory corruption detected in region: {$region}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CorruptionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
