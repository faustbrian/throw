<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

use Cline\Throw\Concerns\ConditionallyThrowable;
use Cline\Throw\Concerns\FiltersExceptions;
use Cline\Throw\Concerns\HasErrorContext;
use Cline\Throw\Concerns\TransformsErrors;
use Cline\Throw\Concerns\WrapsErrors;
use Exception;
use Stringable;
use Throwable;

use function array_any;
use function array_filter;
use function array_values;
use function count;
use function implode;
use function sprintf;

/**
 * Python-inspired exception group for handling multiple exceptions.
 *
 * ExceptionGroup allows you to throw and catch multiple exceptions as a single
 * unit, making it ideal for validation scenarios where multiple errors can occur
 * simultaneously. Inspired by Python 3.11's exception groups.
 *
 * @example Multiple validation errors
 * ```php
 * throw new ExceptionGroup('Validation failed', [
 *     new InvalidEmailException('Invalid email format'),
 *     new WeakPasswordException('Password too weak'),
 *     new RequiredFieldException('Name is required'),
 * ]);
 * ```
 * @example Collect errors and throw once
 * ```php
 * $errors = [];
 * if (!$email) $errors[] = new RequiredFieldException('Email required');
 * if (!$password) $errors[] = new RequiredFieldException('Password required');
 *
 * raise($errors, 'Registration failed');
 * ```
 * @example Filter by exception type
 * ```php
 * try {
 *     // operation
 * } catch (ExceptionGroup $eg) {
 *     $validationErrors = $eg->filter(ValidationException::class);
 *     foreach ($validationErrors as $error) {
 *         Log::warning($error->getMessage());
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class ExceptionGroup extends Exception implements Stringable
{
    use ConditionallyThrowable;
    use FiltersExceptions;
    use HasErrorContext;
    use TransformsErrors;
    use WrapsErrors;

    /**
     * The exceptions contained in this group.
     *
     * @var array<int, Throwable>
     */
    private array $exceptions = [];

    /**
     * Create a new exception group.
     *
     * @param string                $message    Description of the error group
     * @param array<int, Throwable> $exceptions Array of exceptions to group
     * @param int                   $code       Optional error code
     * @param null|Throwable        $previous   Optional previous exception
     */
    public function __construct(
        string $message,
        array $exceptions = [],
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);

        $this->exceptions = array_values($exceptions);
    }

    /**
     * Get string representation of exception group.
     */
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * Create an exception group from multiple exceptions.
     *
     * @param array<int, Throwable> $exceptions Exceptions to group
     * @param string                $message    Optional group message
     *
     * @example Create from array
     * ```php
     * $group = ExceptionGroup::from([
     *     new ValidationException('Email invalid'),
     *     new ValidationException('Password required'),
     * ], 'Validation failed');
     * ```
     */
    public static function from(array $exceptions, string $message = 'Multiple exceptions occurred'): self
    {
        return new self($message, $exceptions);
    }

    /**
     * Get all exceptions in this group.
     *
     * @return array<int, Throwable>
     *
     * @example Iterate all exceptions
     * ```php
     * foreach ($exceptionGroup->getExceptions() as $exception) {
     *     logger()->error($exception->getMessage());
     * }
     * ```
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * Filter exceptions by type.
     *
     * @template T of Throwable
     *
     * @param  class-string<T> $type The exception class to filter by
     * @return array<int, T>   Filtered array of exceptions
     *
     * @example Filter validation errors
     * ```php
     * $validationErrors = $group->filter(ValidationException::class);
     * foreach ($validationErrors as $error) {
     *     // Handle validation error
     * }
     * ```
     */
    public function filter(string $type): array
    {
        /** @var array<int, T> */
        return array_values(
            array_filter(
                $this->exceptions,
                fn (Throwable $e): bool => $e instanceof $type,
            ),
        );
    }

    /**
     * Check if this group contains a specific exception type.
     *
     * @param class-string<Throwable> $type Exception class to check for
     *
     * @example Check for validation errors
     * ```php
     * if ($group->has(ValidationException::class)) {
     *     // Handle validation errors
     * }
     * ```
     */
    public function has(string $type): bool
    {
        return array_any($this->exceptions, fn ($exception): bool => $exception instanceof $type);
    }

    /**
     * Get the count of exceptions in this group.
     *
     * @example Display error count
     * ```php
     * echo "Found {$group->count()} errors";
     * ```
     */
    public function count(): int
    {
        return count($this->exceptions);
    }

    /**
     * Check if this group is empty.
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Get a formatted string representation of all exceptions.
     *
     * @example Log all errors
     * ```php
     * logger()->error($group->format());
     * ```
     */
    public function format(): string
    {
        $lines = [$this->getMessage()];

        foreach ($this->exceptions as $i => $exception) {
            $lines[] = sprintf(
                '  [%d] %s: %s',
                $i + 1,
                $exception::class,
                $exception->getMessage(),
            );
        }

        return implode("\n", $lines);
    }
}
