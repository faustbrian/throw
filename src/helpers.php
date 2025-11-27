<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw;

use Cline\Throw\Support\Assertion;

use function function_exists;
use function value;

if (!function_exists('Cline\Throw\ensure')) {
    /**
     * Create a fluent assertion that can throw or abort on failure.
     *
     * This helper provides a readable way to make assertions about values
     * and conditionally throw exceptions or abort HTTP requests when the
     * assertion fails.
     *
     * @param bool|callable $condition The condition to assert (bool or callable returning bool)
     *
     * @example Throw on null value
     * ```php
     * ensure($user !== null)->orThrow(UserNotFoundException::class);
     * ```
     * @example Throw with custom exception
     * ```php
     * ensure($token->isValid())
     *     ->orThrow(InvalidTokenException::expired());
     * ```
     * @example Abort HTTP request
     * ```php
     * ensure($user->isAdmin())->orAbort(HttpStatusCode::Forbidden);
     * ```
     * @example Guard against invalid state
     * ```php
     * ensure($order->canBeCancelled())
     *     ->orThrow(OrderException::cannotCancel());
     * ```
     * @example Lazy evaluation with callback
     * ```php
     * ensure(fn() => $user->isAdmin())->orForbidden();
     * ensure(fn() => expensive_check())->orThrow(Exception::class);
     * ```
     */
    function ensure(bool|callable $condition): Assertion
    {
        /** @var bool $resolved */
        $resolved = value($condition);

        return new Assertion($resolved);
    }
}
