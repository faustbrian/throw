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
 * Base exception for registry operation failures.
 *
 * Thrown when registry operations fail, registry entries are invalid,
 * or registry operations encounter errors.
 *
 * @example Registry operation failed
 * ```php
 * final class RegistryOperationException extends RegistryException
 * {
 *     public static function failed(string $operation, string $registry): self
 *     {
 *         return new self("Registry operation '{$operation}' failed for registry: {$registry}");
 *     }
 * }
 * ```
 * @example Registry entry not found
 * ```php
 * final class RegistryEntryNotFoundException extends RegistryException
 * {
 *     public static function detected(string $key, string $registry): self
 *     {
 *         return new self("Registry entry not found for key '{$key}' in registry: {$registry}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RegistryException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
