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
use RuntimeException;

/**
 * Base exception for attribute processing failures.
 *
 * Thrown when attribute processing fails, attributes are invalid,
 * or attribute operations encounter errors.
 *
 * @example Attribute processing failed
 * ```php
 * final class AttributeProcessingException extends AttributeException
 * {
 *     public static function failed(string $attribute, string $target): self
 *     {
 *         return new self("Attribute processing failed for '{$attribute}' on target: {$target}");
 *     }
 * }
 * ```
 * @example Invalid attribute arguments
 * ```php
 * final class InvalidAttributeArgumentsException extends AttributeException
 * {
 *     public static function detected(string $attribute, array $errors): self
 *     {
 *         return new self("Invalid arguments for attribute '{$attribute}': " . json_encode($errors));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AttributeException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
