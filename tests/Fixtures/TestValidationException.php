<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Fixtures;

use Cline\Throw\Concerns\FiltersExceptions;
use Cline\Throw\Concerns\TransformsErrors;
use Cline\Throw\Exceptions\InvalidArgumentException;

/**
 * Test validation exception for testing base exception functionality.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class TestValidationException extends InvalidArgumentException
{
    use FiltersExceptions;
    use TransformsErrors;

    public static function withMessage(string $message): self
    {
        return new self($message);
    }

    public static function invalidEmail(): self
    {
        return new self('Invalid email format');
    }
}
