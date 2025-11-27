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
 * Base exception for secret vault errors.
 *
 * Thrown when vault operations fail, vault is unavailable,
 * or vault authentication encounters errors.
 *
 * @example Vault unavailable
 * ```php
 * final class VaultUnavailableException extends VaultException
 * {
 *     public static function detected(string $vault): self
 *     {
 *         return new self("Vault is unavailable: {$vault}");
 *     }
 * }
 * ```
 * @example Vault authentication failed
 * ```php
 * final class VaultAuthenticationException extends VaultException
 * {
 *     public static function failed(string $method): self
 *     {
 *         return new self("Vault authentication failed using method: {$method}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class VaultException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
