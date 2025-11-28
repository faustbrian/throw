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
 * Base exception for workflow execution errors.
 *
 * Thrown when workflow execution fails, workflow transitions fail,
 * or workflow state management encounters errors.
 *
 * @example Workflow execution failed
 * ```php
 * final class WorkflowExecutionException extends WorkflowException
 * {
 *     public static function failed(string $workflow, string $step): self
 *     {
 *         return new self("Workflow '{$workflow}' failed at step: {$step}");
 *     }
 * }
 * ```
 * @example Invalid workflow transition
 * ```php
 * final class InvalidWorkflowTransitionException extends WorkflowException
 * {
 *     public static function detected(string $from, string $to): self
 *     {
 *         return new self("Invalid workflow transition from '{$from}' to '{$to}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class WorkflowException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
