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
 * Base exception for external system failures.
 *
 * Infrastructure exceptions represent failures in external dependencies such as
 * databases, APIs, file systems, caches, queues, or other infrastructure components.
 * These errors indicate technical failures outside the application's direct control.
 *
 * @example Database connection failure
 * ```php
 * final class DatabaseConnectionException extends InfrastructureException
 * {
 *     public static function failed(string $host): self
 *     {
 *         return new self("Failed to connect to database at {$host}");
 *     }
 * }
 * ```
 * @example External API failure
 * ```php
 * final class PaymentGatewayException extends InfrastructureException
 * {
 *     public static function timeout(): self
 *     {
 *         return new self('Payment gateway request timed out');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InfrastructureException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
