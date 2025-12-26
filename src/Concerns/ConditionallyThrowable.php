<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Concerns;

use Cline\Throw\Support\HttpStatusCode;

use function abort_if;
use function throw_if;
use function value;

/**
 * Provides fluent conditional exception throwing and HTTP abort methods.
 *
 * Enables readable guard clauses by allowing exceptions to be conditionally thrown
 * based on boolean conditions or lazy-evaluated callbacks. Supports both exception
 * throwing for general error handling and HTTP abort for web request termination.
 *
 * ```php
 * // Conditional throwing with static factory pattern
 * MissingTokenableException::forParentToken()->throwIf($tokenable === null);
 * InvalidTokenableException::mustImplementHasApiTokens()
 *     ->throwUnless($tokenable instanceof HasApiTokens);
 *
 * // Lazy evaluation to defer expensive checks
 * ExpensiveCheckException::failed()->throwIf(fn() => $db->checkExists($id));
 *
 * // HTTP abort with status codes
 * UnauthorizedException::invalidCredentials()
 *     ->abortIf(!$user, HttpStatusCode::Unauthorized);
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
trait ConditionallyThrowable
{
    /**
     * Throws this exception when the condition evaluates to true.
     *
     * Enables fluent guard clauses by chaining with exception factory methods.
     * Accepts both direct boolean values and callables for lazy evaluation,
     * deferring expensive checks until the condition is actually evaluated.
     *
     * @param bool|callable(): bool $condition Condition to evaluate, or callable returning boolean
     *
     * @throws static When condition evaluates to true
     *
     * ```php
     * // Direct boolean evaluation
     * MissingTokenableException::forParentToken()->throwIf($tokenable === null);
     *
     * // Lazy evaluation defers expensive database check
     * ExpensiveCheckException::failed()->throwIf(fn() => !$db->exists($id));
     * ```
     */
    public function throwIf(bool|callable $condition): void
    {
        /** @var bool $resolved */
        $resolved = value($condition);

        throw_if($resolved, $this);
    }

    /**
     * Throws this exception when the condition evaluates to false.
     *
     * Inverse of throwIf. Useful for validation scenarios where you assert
     * that a condition must be true, throwing when the requirement is not met.
     *
     * @param bool|callable(): bool $condition Condition to evaluate, or callable returning boolean
     *
     * @throws static When condition evaluates to false
     *
     * ```php
     * // Ensure interface compliance
     * InvalidTokenableException::mustImplementHasApiTokens()
     *     ->throwUnless($tokenable instanceof HasApiTokens);
     *
     * // Lazy permission check
     * UnauthorizedException::missingPermission()
     *     ->throwUnless(fn() => $auth->user()->can('admin'));
     * ```
     */
    public function throwUnless(bool|callable $condition): void
    {
        /** @var bool $resolved */
        $resolved = value($condition);

        $this->throwIf(!$resolved);
    }

    /**
     * Terminates HTTP request with status code when condition is true.
     *
     * Immediately halts request processing and returns an HTTP error response
     * using the exception's message. Preferred over throwing in HTTP contexts
     * where standardized error responses are required.
     *
     * @param bool|callable(): bool $condition Condition to evaluate, or callable returning boolean
     * @param HttpStatusCode        $code      HTTP status code to return (default: 500 Internal Server Error)
     */
    public function abortIf(bool|callable $condition, HttpStatusCode $code = HttpStatusCode::InternalServerError): void
    {
        /** @var bool $resolved */
        $resolved = value($condition);

        abort_if($resolved, $code->value, $this->getMessage());
    }

    /**
     * Terminates HTTP request with status code when condition is false.
     *
     * Inverse of abortIf. Useful for authorization checks where access must
     * be granted, terminating the request when requirements are not met.
     *
     * @param bool|callable(): bool $condition Condition to evaluate, or callable returning boolean
     * @param HttpStatusCode        $code      HTTP status code to return (default: 500 Internal Server Error)
     */
    public function abortUnless(bool|callable $condition, HttpStatusCode $code = HttpStatusCode::InternalServerError): void
    {
        /** @var bool $resolved */
        $resolved = value($condition);

        $this->abortIf(!$resolved, $code);
    }
}
