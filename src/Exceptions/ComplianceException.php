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
 * Base exception for compliance violation errors.
 *
 * Thrown when compliance requirements are violated, compliance checks fail,
 * or compliance operations encounter errors.
 *
 * @example GDPR violation
 * ```php
 * final class GdprViolationException extends ComplianceException
 * {
 *     public static function dataRetention(string $entity): self
 *     {
 *         return new self("GDPR data retention violation for entity: {$entity}");
 *     }
 * }
 * ```
 * @example PCI-DSS violation
 * ```php
 * final class PciDssViolationException extends ComplianceException
 * {
 *     public static function detected(string $violation): self
 *     {
 *         return new self("PCI-DSS compliance violation: {$violation}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ComplianceException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
