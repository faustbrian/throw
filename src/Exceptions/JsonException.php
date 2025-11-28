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
 * Base exception for JSON operation errors.
 *
 * Thrown when JSON encoding/decoding fails, JSON is malformed,
 * or JSON operations encounter errors.
 *
 * @example JSON encoding failed
 * ```php
 * final class JsonEncodingException extends JsonException
 * {
 *     public static function failed(mixed $data, string $error): self
 *     {
 *         return new self("JSON encoding failed: {$error}");
 *     }
 * }
 * ```
 * @example JSON decoding failed
 * ```php
 * final class JsonDecodingException extends JsonException
 * {
 *     public static function failed(string $json, string $error): self
 *     {
 *         return new self("JSON decoding failed: {$error}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class JsonException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
