<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Exceptions;

use Exception;

use function sprintf;

/**
 * Exception thrown when a test expectation fails.
 *
 * Used in tests when an expected exception should have been thrown
 * but wasn't, indicating the test has failed.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class TestExpectationFailedException extends Exception
{
    /**
     * Create exception for expected throw that didn't happen.
     *
     * @param class-string $expectedClass The exception class that was expected to be thrown
     */
    public static function expectedThrow(string $expectedClass): self
    {
        return new self(sprintf('Expected %s to be thrown', $expectedClass));
    }
}
