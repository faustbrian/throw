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
 * Base exception for cloud storage failures.
 *
 * Thrown when cloud storage operations fail (S3, Azure Blob, Google Cloud
 * Storage, etc.). Extends InfrastructureException for storage services.
 *
 * @example Upload failed
 * ```php
 * final class StorageUploadException extends StorageException
 * {
 *     public static function failed(string $path): self
 *     {
 *         return new self("Failed to upload to storage: {$path}");
 *     }
 * }
 * ```
 * @example Bucket not found
 * ```php
 * final class BucketNotFoundException extends StorageException
 * {
 *     public static function named(string $bucket): self
 *     {
 *         return new self("Storage bucket not found: {$bucket}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class StorageException extends InfrastructureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
