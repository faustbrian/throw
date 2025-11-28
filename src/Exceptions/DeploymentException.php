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
 * Base exception for deployment operation failures.
 *
 * Thrown when deployment operations fail, deployments cannot start,
 * or deployment lifecycle encounters errors.
 *
 * @example Deployment failed
 * ```php
 * final class DeploymentFailedException extends DeploymentException
 * {
 *     public static function forVersion(string $version, string $environment): self
 *     {
 *         return new self("Deployment of version '{$version}' to {$environment} failed");
 *     }
 * }
 * ```
 * @example Deployment timeout
 * ```php
 * final class DeploymentTimeoutException extends DeploymentException
 * {
 *     public static function after(string $deployment, int $timeout): self
 *     {
 *         return new self("Deployment '{$deployment}' timed out after {$timeout} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DeploymentException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
