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
 * Base exception for invoice generation failures.
 *
 * Thrown when invoice generation fails, invoice calculations fail,
 * or invoice operations encounter errors.
 *
 * @example Invoice generation failed
 * ```php
 * final class InvoiceGenerationException extends InvoiceException
 * {
 *     public static function failed(string $invoiceId): self
 *     {
 *         return new self("Failed to generate invoice: {$invoiceId}");
 *     }
 * }
 * ```
 * @example Invalid invoice data
 * ```php
 * final class InvalidInvoiceDataException extends InvoiceException
 * {
 *     public static function detected(string $field): self
 *     {
 *         return new self("Invalid invoice data for field: {$field}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class InvoiceException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
