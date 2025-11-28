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
 * Base exception for mocking and stubbing errors.
 *
 * Thrown when mock object creation, configuration, or verification fails
 * during testing.
 *
 * @example Mock creation failed
 * ```php
 * final class MockCreationException extends MockException
 * {
 *     public static function failed(string $class): self
 *     {
 *         return new self("Failed to create mock for {$class}");
 *     }
 * }
 * ```
 * @example Unexpected method call
 * ```php
 * final class UnexpectedMethodCallException extends MockException
 * {
 *     public static function detected(string $method): self
 *     {
 *         return new self("Unexpected call to mocked method: {$method}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MockException extends TestException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
