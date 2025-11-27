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
 * Base exception for malformed HTTP requests.
 *
 * Thrown when HTTP requests are malformed, missing required data,
 * or violate API contracts. Maps to HTTP 400 Bad Request.
 *
 * @example Missing parameter
 * ```php
 * final class MissingParameterException extends BadRequestException
 * {
 *     public static function required(string $parameter): self
 *     {
 *         return new self("Required parameter missing: {$parameter}");
 *     }
 * }
 * ```
 * @example Invalid JSON
 * ```php
 * final class InvalidJsonException extends BadRequestException
 * {
 *     public static function malformed(): self
 *     {
 *         return new self('Request body contains malformed JSON');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BadRequestException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
