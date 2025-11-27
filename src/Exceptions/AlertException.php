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
 * Exception for alert triggering errors.
 *
 * Thrown when alert triggering fails, alert conditions are invalid,
 * or alert operations encounter errors.
 *
 * @example Alert triggering failed
 * ```php
 * final class AlertTriggerException extends AlertException
 * {
 *     public static function failed(string $alert, string $reason): self
 *     {
 *         return new self("Alert '{$alert}' triggering failed: {$reason}");
 *     }
 * }
 * ```
 * @example Invalid alert condition
 * ```php
 * final class InvalidAlertConditionException extends AlertException
 * {
 *     public static function detected(string $alert, string $condition): self
 *     {
 *         return new self("Invalid alert condition for '{$alert}': {$condition}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AlertException extends NotificationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
