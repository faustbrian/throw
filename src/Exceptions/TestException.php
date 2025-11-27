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
 * Base exception for test execution failures.
 *
 * Thrown when test execution fails, test setup encounters errors,
 * or test teardown operations fail.
 *
 * @example Test execution failed
 * ```php
 * final class TestExecutionException extends TestException
 * {
 *     public static function failed(string $testName, string $reason): self
 *     {
 *         return new self("Test '{$testName}' execution failed: {$reason}");
 *     }
 * }
 * ```
 * @example Test setup failed
 * ```php
 * final class TestSetupException extends TestException
 * {
 *     public static function failed(string $testClass): self
 *     {
 *         return new self("Test setup failed for class: {$testClass}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TestException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
