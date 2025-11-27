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
 * Exception for ViewModel creation failures.
 *
 * Thrown when ViewModel creation fails, ViewModel data is invalid,
 * or ViewModel operations encounter errors.
 *
 * @example ViewModel creation failed
 * ```php
 * final class ViewModelCreationException extends ViewModelException
 * {
 *     public static function failed(string $viewModel, string $reason): self
 *     {
 *         return new self("ViewModel '{$viewModel}' creation failed: {$reason}");
 *     }
 * }
 * ```
 * @example Invalid ViewModel data
 * ```php
 * final class InvalidViewModelDataException extends ViewModelException
 * {
 *     public static function detected(string $viewModel, array $errors): self
 *     {
 *         return new self("Invalid data for ViewModel '{$viewModel}': " . json_encode($errors));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ViewModelException extends PresenterException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
