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
 * Exception for chunk processing failures.
 *
 * Thrown when chunk processing fails, chunk size is invalid,
 * or chunk operations encounter errors.
 *
 * @example Chunk processing failed
 * ```php
 * final class ChunkProcessingException extends ChunkException
 * {
 *     public static function forChunk(int $chunkNumber, int $totalChunks): self
 *     {
 *         return new self("Chunk processing failed: chunk {$chunkNumber}/{$totalChunks}");
 *     }
 * }
 * ```
 * @example Invalid chunk size
 * ```php
 * final class InvalidChunkSizeException extends ChunkException
 * {
 *     public static function detected(int $size, int $minSize, int $maxSize): self
 *     {
 *         return new self("Invalid chunk size {$size}: must be between {$minSize} and {$maxSize}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ChunkException extends BatchException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
