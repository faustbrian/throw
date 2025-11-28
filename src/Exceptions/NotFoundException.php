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
 * Base exception for resource not found errors.
 *
 * Thrown when a requested resource, entity, or record cannot be found.
 * This is clearer than generic RuntimeException for "not found" scenarios.
 *
 * @example Model not found
 * ```php
 * final class UserNotFoundException extends NotFoundException
 * {
 *     public static function withId(int $id): self
 *     {
 *         return new self("User with ID {$id} not found");
 *     }
 * }
 * ```
 * @example File not found
 * ```php
 * final class FileNotFoundException extends NotFoundException
 * {
 *     public static function atPath(string $path): self
 *     {
 *         return new self("File not found: {$path}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class NotFoundException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
