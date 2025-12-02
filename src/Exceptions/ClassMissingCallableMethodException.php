<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

use function sprintf;

/**
 * Exception thrown when a class does not have __invoke or handle method.
 *
 * This exception is thrown when attempting to resolve a class as a callable,
 * but the class lacks both __invoke and handle methods required for invocation.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class ClassMissingCallableMethodException extends InvalidArgumentException
{
    /**
     * Create exception for a class missing callable methods.
     *
     * @param class-string $class The class that is missing callable methods
     */
    public static function forClass(string $class): self
    {
        return new self(sprintf('Class %s must have __invoke or handle method', $class));
    }
}
