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
 * Exception for service binding and resolution failures.
 *
 * Thrown when service bindings fail, abstract types cannot be resolved,
 * or when attempting to bind already bound services.
 *
 * @example Binding already exists
 * ```php
 * final class BindingAlreadyExistsException extends BindingException
 * {
 *     public static function forService(string $service): self
 *     {
 *         return new self("Service already bound: {$service}");
 *     }
 * }
 * ```
 * @example Cannot resolve abstract
 * ```php
 * final class UnresolvableAbstractException extends BindingException
 * {
 *     public static function failed(string $abstract): self
 *     {
 *         return new self("Cannot resolve abstract type: {$abstract}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BindingException extends ContainerException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
