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
 * Base exception for resource allocation failures.
 *
 * Thrown when resource allocation fails, resources are unavailable,
 * or resource operations encounter errors.
 *
 * @example Resource allocation failed
 * ```php
 * final class ResourceAllocationException extends ResourceException
 * {
 *     public static function failed(string $resource, string $reason): self
 *     {
 *         return new self("Resource allocation failed for '{$resource}': {$reason}");
 *     }
 * }
 * ```
 * @example Resource exhausted
 * ```php
 * final class ResourceExhaustedException extends ResourceException
 * {
 *     public static function detected(string $resource, int $limit): self
 *     {
 *         return new self("Resource '{$resource}' exhausted: limit of {$limit} reached");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ResourceException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
