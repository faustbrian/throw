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
 * This is commonly encountered when passing objects to dependency injection
 * containers, command buses, or job queues that expect invocable objects.
 *
 * @example Validate job object
 * ```php
 * if (!method_exists($job, '__invoke') && !method_exists($job, 'handle')) {
 *     throw ObjectMissingCallableMethodException::create();
 * }
 * ```
 * @example Command bus validation
 * ```php
 * public function dispatch(object $command): void
 * {
 *     if (!is_callable($command) && !method_exists($command, 'handle')) {
 *         throw ObjectMissingCallableMethodException::create();
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class ObjectMissingCallableMethodException extends InvalidArgumentException
{
    /**
     * Create exception for an object missing callable methods.
     *
     * @return self The exception instance
     *
     * @example Queue job validation
     * ```php
     * $handler = $this->container->make($jobClass);
     * if (!is_callable($handler) && !method_exists($handler, 'handle')) {
     *     throw ObjectMissingCallableMethodException::create();
     * }
     * ```
     */
    public static function create(): self
    {
        return new self('Object must have __invoke or handle method');
    }
}
