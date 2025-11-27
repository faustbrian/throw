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
 * Base exception for SSL/TLS certificate errors.
 *
 * Thrown when certificate validation fails, certificates are expired,
 * or certificate operations encounter errors.
 *
 * @example Certificate expired
 * ```php
 * final class CertificateExpiredException extends CertificateException
 * {
 *     public static function forDomain(string $domain, string $expiry): self
 *     {
 *         return new self("Certificate for '{$domain}' expired on {$expiry}");
 *     }
 * }
 * ```
 * @example Invalid certificate
 * ```php
 * final class InvalidCertificateException extends CertificateException
 * {
 *     public static function untrusted(string $issuer): self
 *     {
 *         return new self("Certificate from untrusted issuer: {$issuer}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CertificateException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
