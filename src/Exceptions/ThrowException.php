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
 * This interface extends PHP's Throwable interface and serves as a marker
 * to identify exceptions that originate from the Throw package. By catching
 * this interface, consumers can handle all Throw package exceptions uniformly
 * while allowing other exceptions to propagate normally.
 *
 * As a marker interface, it declares no additional methods beyond those
 * inherited from Throwable. Its sole purpose is type identification and
 * enabling selective exception handling.
 *
 * All custom exceptions in the Throw package should implement this interface
 * either directly or through inheritance from RuntimeException or
 * InvalidArgumentException.
 *
 * @example Catch all package exceptions
 * ```php
 * try {
 *     $result = attempt(fn() => riskyOperation())->get();
 * } catch (ThrowException $e) {
 *     // Handle any Throw package exception
 *     logger()->error('Throw package error', ['exception' => $e]);
 * }
 * ```
 * @example Selective exception handling
 * ```php
 * try {
 *     $user = User::findOrFail($id);
 *     ensure($user->isActive())->orThrow(InactiveUserException::class);
 * } catch (ThrowException $e) {
 *     // Handle Throw package exceptions
 *     return response()->json(['error' => $e->getMessage()], 400);
 * } catch (Throwable $e) {
 *     // Handle other exceptions (like Eloquent ModelNotFoundException)
 *     throw $e;
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 *
 * @see RuntimeException Base exception class for runtime errors
 * @see InvalidArgumentException Base exception class for invalid arguments
 */
interface ThrowException extends Throwable
{
    // Marker interface - no methods required
}
