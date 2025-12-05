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
use RuntimeException as PhpRuntimeException;

/**
 * Exception thrown when an error which can only be found on runtime occurs.
 *
 * Thrown when encountering errors that can only be detected during program execution,
 * such as resource failures, I/O errors, or external service failures.
 *
 * @example File operation failure
 * ```php
 * final class FileOperationException extends RuntimeException
 * {
 *     public static function cannotRead(string $path): self
 *     {
 *         return new self("Failed to read file: {$path}");
 *     }
 * }
 * ```
 * @example Service unavailable
 * ```php
 * final class ServiceUnavailableException extends RuntimeException
 * {
 *     public static function named(string $service): self
 *     {
 *         return new self("Service '{$service}' is currently unavailable");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RuntimeException extends PhpRuntimeException implements ThrowException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
