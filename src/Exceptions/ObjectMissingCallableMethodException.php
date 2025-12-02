<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

/**
 * Exception thrown when an object does not have __invoke or handle method.
 *
 * This exception is thrown when attempting to resolve an object as a callable,
 * but the object lacks both __invoke and handle methods required for invocation.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class ObjectMissingCallableMethodException extends InvalidArgumentException
{
    /**
     * Create exception for an object missing callable methods.
     */
    public static function create(): self
    {
        return new self('Object must have __invoke or handle method');
    }
}
