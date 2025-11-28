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
 * Base exception for builder pattern errors.
 *
 * Thrown when builder operations fail, required builder methods are missing,
 * or builder construction encounters errors.
 *
 * @example Builder operation failed
 * ```php
 * final class BuilderOperationException extends BuilderException
 * {
 *     public static function failed(string $builder, string $operation): self
 *     {
 *         return new self("Builder '{$builder}' operation '{$operation}' failed");
 *     }
 * }
 * ```
 * @example Incomplete builder
 * ```php
 * final class IncompleteBuilderException extends BuilderException
 * {
 *     public static function detected(string $builder, array $missingFields): self
 *     {
 *         return new self("Builder '{$builder}' incomplete: missing fields " . implode(', ', $missingFields));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BuilderException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
