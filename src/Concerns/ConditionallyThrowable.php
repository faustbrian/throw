<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Concerns;

use function abort_if;
use function throw_if;

/**
 * Trait for adding fluent conditional throwing to exceptions.
 *
 * This trait provides methods to conditionally throw exceptions or abort requests
 * with HTTP status codes based on boolean conditions. It enables a more readable
 * fluent interface for exception handling.
 *
 * @example Basic usage with throwIf
 * ```php
 * MissingTokenableException::forParentToken()->throwIf($tokenable === null);
 * ```
 * @example Basic usage with throwUnless
 * ```php
 * InvalidTokenableException::mustImplementHasApiTokens()->throwUnless($tokenable instanceof HasApiTokens);
 * ```
 * @example Abort with custom status code
 * ```php
 * UnauthorizedException::invalidCredentials()->abortIf(!$user, 401);
 * ```
 * @example Abort unless authorized
 * ```php
 * ForbiddenException::missingPermission()->abortUnless($user->can('admin'), 403);
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
trait ConditionallyThrowable
{
    /**
     * Throw the exception if the given condition is true.
     *
     * Provides a fluent interface for conditional exception throwing. This method
     * enables chaining with static factory methods on exceptions, making guard
     * clauses more readable by keeping the exception construction and throwing
     * logic together.
     *
     * @param bool $condition The condition to evaluate
     *
     * @throws static When the condition evaluates to true
     *
     * @example Guard against null values
     * ```php
     * MissingTokenableException::forParentToken()->throwIf($tokenable === null);
     * ```
     * @example Guard against invalid types
     * ```php
     * InvalidTypeException::expectedString()->throwIf(!is_string($value));
     * ```
     */
    public function throwIf(bool $condition): void
    {
        throw_if($condition, $this);
    }

    /**
     * Throw the exception unless the given condition is true.
     *
     * Inverse of throwIf - throws when the condition is false. Useful for
     * validation scenarios where you want to ensure a condition is met,
     * throwing an exception when it isn't.
     *
     * @param bool $condition The condition to evaluate
     *
     * @throws static When the condition evaluates to false
     *
     * @example Ensure interface implementation
     * ```php
     * InvalidTokenableException::mustImplementHasApiTokens()
     *     ->throwUnless($tokenable instanceof HasApiTokens);
     * ```
     * @example Ensure permission granted
     * ```php
     * UnauthorizedException::missingPermission()
     *     ->throwUnless($user->hasPermission('admin'));
     * ```
     */
    public function throwUnless(bool $condition): void
    {
        $this->throwIf(!$condition);
    }

    /**
     * Abort the request with an HTTP status code if the condition is true.
     *
     * Terminates request processing and returns an HTTP error response when
     * the condition is met. Useful in HTTP contexts where you want to return
     * standardized error responses instead of throwing exceptions.
     *
     * @param bool $condition The condition to evaluate
     * @param int  $code      HTTP status code to return (default: 500)
     *
     * @example Abort on authentication failure
     * ```php
     * UnauthorizedException::invalidCredentials()->abortIf(!$user, 401);
     * ```
     * @example Abort on resource not found
     * ```php
     * NotFoundException::resourceMissing()->abortIf($resource === null, 404);
     * ```
     */
    public function abortIf(bool $condition, int $code = 500): void
    {
        abort_if($condition, $code, $this->getMessage());
    }

    /**
     * Abort the request with an HTTP status code unless the condition is true.
     *
     * Inverse of abortIf - aborts when the condition is false. Useful for
     * authorization checks where you want to ensure access is granted.
     *
     * @param bool $condition The condition to evaluate
     * @param int  $code      HTTP status code to return (default: 500)
     *
     * @example Abort unless authorized
     * ```php
     * ForbiddenException::missingPermission()
     *     ->abortUnless($user->can('admin'), 403);
     * ```
     * @example Abort unless resource exists
     * ```php
     * NotFoundException::resourceMissing()
     *     ->abortUnless($resource !== null, 404);
     * ```
     */
    public function abortUnless(bool $condition, int $code = 500): void
    {
        $this->abortIf(!$condition, $code);
    }
}
