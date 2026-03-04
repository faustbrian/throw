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
 * Used in dependency injection, event handlers, middleware, and other
 * contexts where callables are expected.
 *
 * @example Validate callable handler
 * ```php
 * if (!is_callable($handler) && !is_string($handler) && !is_object($handler)) {
 *     throw InvalidCallableException::create();
 * }
 * ```
 * @example Event dispatcher validation
 * ```php
 * public function on(string $event, mixed $handler): void
 * {
 *     if (!$this->isValidCallable($handler)) {
 *         throw InvalidCallableException::create();
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class InvalidCallableException extends InvalidArgumentException
{
    /**
     * Create exception for an invalid callable.
     *
     * @return self The exception instance
     *
     * @example Middleware validation
     * ```php
     * try {
     *     $this->resolveMiddleware($middleware);
     * } catch (ReflectionException $e) {
     *     throw InvalidCallableException::create();
     * }
     * ```
     */
    public static function create(): self
    {
        return new self('Invalid callable provided');
    }
}
