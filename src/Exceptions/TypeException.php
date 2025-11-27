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
 * Base exception for type mismatches.
 *
 * Thrown when values are of incorrect types. Clearer and more specific
 * than InvalidArgumentException for type-related errors.
 *
 * @example Expected array
 * ```php
 * final class ExpectedArrayException extends TypeException
 * {
 *     public static function got(string $actual): self
 *     {
 *         return new self("Expected array, got {$actual}");
 *     }
 * }
 * ```
 * @example Type mismatch
 * ```php
 * final class TypeMismatchException extends TypeException
 * {
 *     public static function expected(string $expected, string $actual): self
 *     {
 *         return new self("Expected {$expected}, got {$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TypeException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
