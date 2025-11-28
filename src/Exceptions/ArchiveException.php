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
 * Base exception for archive creation and extraction errors.
 *
 * Thrown when archive operations fail (zip, tar, etc.),
 * extraction fails, or archive files are corrupted.
 *
 * @example Extraction failed
 * ```php
 * final class ArchiveExtractionException extends ArchiveException
 * {
 *     public static function failed(string $archive, string $destination): self
 *     {
 *         return new self("Failed to extract archive '{$archive}' to: {$destination}");
 *     }
 * }
 * ```
 * @example Corrupted archive
 * ```php
 * final class CorruptedArchiveException extends ArchiveException
 * {
 *     public static function detected(string $archive): self
 *     {
 *         return new self("Archive file is corrupted: {$archive}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ArchiveException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
