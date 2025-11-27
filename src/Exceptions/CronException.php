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
 * Exception for cron expression errors.
 *
 * Thrown when cron expression parsing fails, expressions are invalid,
 * or cron evaluation encounters errors.
 *
 * @example Invalid cron expression
 * ```php
 * final class InvalidCronExpressionException extends CronException
 * {
 *     public static function detected(string $expression): self
 *     {
 *         return new self("Invalid cron expression: {$expression}");
 *     }
 * }
 * ```
 * @example Cron parsing failed
 * ```php
 * final class CronParsingException extends CronException
 * {
 *     public static function failed(string $expression, string $reason): self
 *     {
 *         return new self("Cron expression parsing failed for '{$expression}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CronException extends ScheduleException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
