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
 * Base exception for pipe communication errors.
 *
 * Thrown when pipe operations fail, pipes are broken,
 * or inter-process communication encounters errors.
 *
 * @example Broken pipe
 * ```php
 * final class BrokenPipeException extends PipeException
 * {
 *     public static function detected(): self
 *     {
 *         return new self('Broken pipe: reader has closed the connection');
 *     }
 * }
 * ```
 * @example Pipe creation failed
 * ```php
 * final class PipeCreationException extends PipeException
 * {
 *     public static function failed(): self
 *     {
 *         return new self('Failed to create pipe for inter-process communication');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PipeException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
