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
 * Base exception for generic API errors.
 *
 * Thrown when API operations fail. Generic wrapper for API-related
 * errors. Extends InfrastructureException for external API calls.
 *
 * @example API request failed
 * ```php
 * final class ApiRequestException extends ApiException
 * {
 *     public static function failed(string $endpoint): self
 *     {
 *         return new self("API request failed: {$endpoint}");
 *     }
 * }
 * ```
 * @example API response error
 * ```php
 * final class ApiResponseException extends ApiException
 * {
 *     public static function invalidStatus(int $status): self
 *     {
 *         return new self("API returned error status: {$status}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ApiException extends InfrastructureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
