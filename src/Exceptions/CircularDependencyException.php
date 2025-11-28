<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

use Cline\Throw\Concerns\ConditionallyThrowable;
use Cline\Throw\Concerns\HasErrorContext;
use Cline\Throw\Concerns\WrapsErrors;

/**
 * Exception for circular dependency detection.
 *
 * Thrown when the dependency injection container detects a circular
 * dependency chain during service resolution.
 *
 * @example Circular dependency detected
 * ```php
 * final class CircularDependencyDetectedException extends CircularDependencyException
 * {
 *     public static function inChain(array $chain): self
 *     {
 *         $services = implode(' -> ', $chain);
 *         return new self("Circular dependency detected: {$services}");
 *     }
 * }
 * ```
 * @example Self-referencing dependency
 * ```php
 * final class SelfReferencingException extends CircularDependencyException
 * {
 *     public static function forService(string $service): self
 *     {
 *         return new self("Service depends on itself: {$service}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CircularDependencyException extends ContainerException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
