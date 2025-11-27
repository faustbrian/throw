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
 * Base exception for data table rendering errors.
 *
 * Thrown when data table rendering fails, table configuration is invalid,
 * or table operations encounter errors.
 *
 * @example Data table rendering failed
 * ```php
 * final class DataTableRenderingException extends DataTableException
 * {
 *     public static function failed(string $table, string $reason): self
 *     {
 *         return new self("Data table '{$table}' rendering failed: {$reason}");
 *     }
 * }
 * ```
 * @example Invalid table configuration
 * ```php
 * final class InvalidTableConfigException extends DataTableException
 * {
 *     public static function detected(string $table, string $issue): self
 *     {
 *         return new self("Invalid configuration for data table '{$table}': {$issue}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DataTableException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
