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
 * Base exception for cloud storage failures.
 *
 * Thrown when cloud storage operations fail (S3, GCS, Azure Blob, etc.),
 * uploads/downloads fail, or bucket operations encounter errors.
 *
 * @example Upload failed
 * ```php
 * final class CloudStorageUploadException extends CloudStorageException
 * {
 *     public static function failed(string $file, string $bucket): self
 *     {
 *         return new self("Failed to upload '{$file}' to bucket: {$bucket}");
 *     }
 * }
 * ```
 * @example Bucket not found
 * ```php
 * final class BucketNotFoundException extends CloudStorageException
 * {
 *     public static function forName(string $bucket): self
 *     {
 *         return new self("Cloud storage bucket not found: {$bucket}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CloudStorageException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
