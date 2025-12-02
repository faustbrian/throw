<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

use Throwable;

/**
 * Marker interface for all Throw package exceptions.
 *
 * Consumers can catch this interface to handle any exception
 * thrown by the Throw package.
 *
 * @example Catch all package exceptions
 * ```php
 * try {
 *     $result = attempt(fn() => riskyOperation())->get();
 * } catch (ThrowException $e) {
 *     // Handle any Throw package exception
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
interface ThrowException extends Throwable
{
    // Marker interface - no methods required
}
