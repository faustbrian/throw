<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

/**
 * Exception thrown when an invalid callable is provided.
 *
 * This exception is thrown when the provided value cannot be resolved
 * to any valid callable form (closure, function, class, or object).
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class InvalidCallableException extends InvalidArgumentException
{
    /**
     * Create exception for an invalid callable.
     */
    public static function create(): self
    {
        return new self('Invalid callable provided');
    }
}
