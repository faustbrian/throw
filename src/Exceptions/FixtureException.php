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
 * Exception for test fixture loading errors.
 *
 * Thrown when test fixtures fail to load, fixture data is invalid,
 * or fixture initialization encounters errors.
 *
 * @example Fixture loading failed
 * ```php
 * final class FixtureLoadException extends FixtureException
 * {
 *     public static function failed(string $fixtureName): self
 *     {
 *         return new self("Fixture failed to load: {$fixtureName}");
 *     }
 * }
 * ```
 * @example Invalid fixture data
 * ```php
 * final class InvalidFixtureException extends FixtureException
 * {
 *     public static function detected(string $fixtureName, string $reason): self
 *     {
 *         return new self("Invalid fixture data in '{$fixtureName}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FixtureException extends TestException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
