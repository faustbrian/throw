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
 * Base exception for benchmarking errors.
 *
 * Thrown when benchmark operations fail, benchmark configuration is invalid,
 * or benchmark execution encounters errors.
 *
 * @example Benchmark failed
 * ```php
 * final class BenchmarkFailedException extends BenchmarkException
 * {
 *     public static function forTest(string $test): self
 *     {
 *         return new self("Benchmark failed for test: {$test}");
 *     }
 * }
 * ```
 * @example Invalid benchmark configuration
 * ```php
 * final class InvalidBenchmarkConfigException extends BenchmarkException
 * {
 *     public static function detected(string $parameter): self
 *     {
 *         return new self("Invalid benchmark configuration parameter: {$parameter}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BenchmarkException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
