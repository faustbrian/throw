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
use Cline\Throw\Exceptions\RuntimeException;

/**
 * Test domain exception for testing base exception functionality.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class TestDomainException extends RuntimeException
{
    use FiltersExceptions;
    use TransformsErrors;

    public static function withMessage(string $message): self
    {
        return new self($message);
    }

    public static function businessRuleViolation(): self
    {
        return new self('Business rule violated');
    }
}
