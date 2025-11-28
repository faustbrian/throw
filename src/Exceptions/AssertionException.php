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
 * Exception for test assertion violations.
 *
 * Thrown when test assertions fail, expected values don't match,
 * or assertion conditions are not met.
 *
 * @example Assertion failed
 * ```php
 * final class AssertionFailedException extends AssertionException
 * {
 *     public static function failed(string $assertion, mixed $expected, mixed $actual): self
 *     {
 *         return new self("Assertion '{$assertion}' failed: expected " . json_encode($expected) . ", got " . json_encode($actual));
 *     }
 * }
 * ```
 * @example Expected value mismatch
 * ```php
 * final class ExpectedValueException extends AssertionException
 * {
 *     public static function detected(string $field, mixed $expected, mixed $actual): self
 *     {
 *         return new self("Expected value mismatch for '{$field}': expected={$expected}, actual={$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AssertionException extends TestException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
