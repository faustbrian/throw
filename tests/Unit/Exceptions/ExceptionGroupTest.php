<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Throw\Exceptions\ExceptionGroup;
use Tests\Fixtures\TestDomainException;

use function Cline\Throw\raise;

describe('ExceptionGroup', function (): void {
    test('creates with message and exceptions', function (): void {
        $exceptions = [
            new RuntimeException('Error 1'),
            new RuntimeException('Error 2'),
        ];

        $group = new ExceptionGroup('Multiple errors', $exceptions);

        expect($group->getMessage())->toBe('Multiple errors')
            ->and($group->getExceptions())->toHaveCount(2)
            ->and($group->count())->toBe(2);
    });

    test('creates empty group', function (): void {
        $group = new ExceptionGroup('No errors');

        expect($group->isEmpty())->toBeTrue()
            ->and($group->count())->toBe(0);
    });

    test('creates from array', function (): void {
        $exceptions = [
            new RuntimeException('Error 1'),
            new InvalidArgumentException('Error 2'),
        ];

        $group = ExceptionGroup::from($exceptions, 'Grouped errors');

        expect($group->getMessage())->toBe('Grouped errors')
            ->and($group->count())->toBe(2);
    });

    test('creates from array with default message', function (): void {
        $exceptions = [new RuntimeException('Error')];

        $group = ExceptionGroup::from($exceptions);

        expect($group->getMessage())->toBe('Multiple exceptions occurred');
    });
});

describe('getExceptions', function (): void {
    test('returns all exceptions', function (): void {
        $exceptions = [
            new RuntimeException('Error 1'),
            new InvalidArgumentException('Error 2'),
            new LogicException('Error 3'),
        ];

        $group = new ExceptionGroup('Errors', $exceptions);

        expect($group->getExceptions())->toBe($exceptions)
            ->and($group->getExceptions())->toHaveCount(3);
    });

    test('returns empty array for empty group', function (): void {
        $group = new ExceptionGroup('No errors');

        expect($group->getExceptions())->toBe([])
            ->and($group->getExceptions())->toBeEmpty();
    });
});

describe('filter', function (): void {
    test('filters exceptions by type', function (): void {
        $group = new ExceptionGroup('Errors', [
            new RuntimeException('Runtime error'),
            new InvalidArgumentException('Invalid argument'),
            new RuntimeException('Another runtime error'),
        ]);

        $filtered = $group->filter(RuntimeException::class);

        expect($filtered)->toHaveCount(2)
            ->and($filtered[0])->toBeInstanceOf(RuntimeException::class)
            ->and($filtered[1])->toBeInstanceOf(RuntimeException::class);
    });

    test('returns empty array when no matches', function (): void {
        $group = new ExceptionGroup('Errors', [
            new RuntimeException('Error'),
        ]);

        $filtered = $group->filter(InvalidArgumentException::class);

        expect($filtered)->toBeEmpty();
    });

    test('filters custom exception types', function (): void {
        $group = new ExceptionGroup('Errors', [
            new TestDomainException('Domain error'),
            new RuntimeException('Runtime error'),
            new TestDomainException('Another domain error'),
        ]);

        $filtered = $group->filter(TestDomainException::class);

        expect($filtered)->toHaveCount(2)
            ->and($filtered[0])->toBeInstanceOf(TestDomainException::class)
            ->and($filtered[1])->toBeInstanceOf(TestDomainException::class);
    });
});

describe('has', function (): void {
    test('returns true when exception type exists', function (): void {
        $group = new ExceptionGroup('Errors', [
            new RuntimeException('Error 1'),
            new InvalidArgumentException('Error 2'),
        ]);

        expect($group->has(RuntimeException::class))->toBeTrue()
            ->and($group->has(InvalidArgumentException::class))->toBeTrue();
    });

    test('returns false when exception type not present', function (): void {
        $group = new ExceptionGroup('Errors', [
            new RuntimeException('Error'),
        ]);

        expect($group->has(InvalidArgumentException::class))->toBeFalse()
            ->and($group->has(LogicException::class))->toBeFalse();
    });

    test('works with custom exception types', function (): void {
        $group = new ExceptionGroup('Errors', [
            new TestDomainException('Error'),
        ]);

        expect($group->has(TestDomainException::class))->toBeTrue()
            ->and($group->has(RuntimeException::class))->toBeFalse();
    });
});

describe('count', function (): void {
    test('returns correct count', function (): void {
        $group = new ExceptionGroup('Errors', [
            new RuntimeException('1'),
            new RuntimeException('2'),
            new RuntimeException('3'),
        ]);

        expect($group->count())->toBe(3);
    });

    test('returns zero for empty group', function (): void {
        $group = new ExceptionGroup('No errors');

        expect($group->count())->toBe(0);
    });
});

describe('isEmpty', function (): void {
    test('returns true for empty group', function (): void {
        $group = new ExceptionGroup('No errors');

        expect($group->isEmpty())->toBeTrue();
    });

    test('returns false for non-empty group', function (): void {
        $group = new ExceptionGroup('Errors', [
            new RuntimeException('Error'),
        ]);

        expect($group->isEmpty())->toBeFalse();
    });
});

describe('format', function (): void {
    test('formats exceptions as string', function (): void {
        $group = new ExceptionGroup('Validation failed', [
            new RuntimeException('Email invalid'),
            new InvalidArgumentException('Password required'),
        ]);

        $formatted = $group->format();

        expect($formatted)->toContain('Validation failed')
            ->and($formatted)->toContain('RuntimeException: Email invalid')
            ->and($formatted)->toContain('InvalidArgumentException: Password required')
            ->and($formatted)->toContain('[1]')
            ->and($formatted)->toContain('[2]');
    });

    test('formats empty group', function (): void {
        $group = new ExceptionGroup('No errors');

        $formatted = $group->format();

        expect($formatted)->toBe('No errors');
    });
});

describe('__toString', function (): void {
    test('returns formatted string', function (): void {
        $group = new ExceptionGroup('Errors', [
            new RuntimeException('Error 1'),
        ]);

        $string = (string) $group;

        expect($string)->toContain('Errors')
            ->and($string)->toContain('RuntimeException: Error 1');
    });
});

describe('raise helper', function (): void {
    test('throws ExceptionGroup with errors', function (): void {
        expect(fn (): mixed => raise([
            new RuntimeException('Error 1'),
            new RuntimeException('Error 2'),
        ], 'Multiple errors'))
            ->toThrow(ExceptionGroup::class, 'Multiple errors');
    });

    test('throws with default message', function (): void {
        expect(fn (): mixed => raise([
            new RuntimeException('Error'),
        ]))
            ->toThrow(ExceptionGroup::class, 'Multiple exceptions occurred');
    });

    test('does not throw with empty array', function (): void {
        $errors = [];

        raise($errors, 'No errors');

        expect(true)->toBeTrue(); // No exception thrown
    });

    test('includes all exceptions in group', function (): void {
        try {
            raise([
                new RuntimeException('Error 1'),
                new InvalidArgumentException('Error 2'),
                new LogicException('Error 3'),
            ], 'Three errors');
        } catch (ExceptionGroup $exceptionGroup) {
            expect($exceptionGroup->count())->toBe(3)
                ->and($exceptionGroup->getExceptions()[0]->getMessage())->toBe('Error 1')
                ->and($exceptionGroup->getExceptions()[1]->getMessage())->toBe('Error 2')
                ->and($exceptionGroup->getExceptions()[2]->getMessage())->toBe('Error 3');

            return;
        }

        throw new Exception('Expected ExceptionGroup to be thrown');
    });
});

describe('real-world usage', function (): void {
    test('validation error collection', function (): void {
        $errors = [];

        $email = '';
        $password = '';
        $name = '';

        $errors[] = new InvalidArgumentException('Email is required');

        $errors[] = new InvalidArgumentException('Password is required');

        $errors[] = new InvalidArgumentException('Name is required');

        expect(fn (): mixed => raise($errors, 'Validation failed'))
            ->toThrow(ExceptionGroup::class);
    });

    test('filter and handle specific errors', function (): void {
        try {
            raise([
                new RuntimeException('System error'),
                new InvalidArgumentException('Invalid input'),
                new InvalidArgumentException('Invalid format'),
            ], 'Multiple errors');
        } catch (ExceptionGroup $exceptionGroup) {
            $inputErrors = $exceptionGroup->filter(InvalidArgumentException::class);

            expect($inputErrors)->toHaveCount(2);

            return;
        }

        throw new Exception('Expected ExceptionGroup to be thrown');
    });

    test('conditional error raising', function (): void {
        $errors = [];

        // Simulate successful validation
        $email = 'valid@example.com';
        $password = 'securePassword123';

        if ($email === '0') {
            $errors[] = new InvalidArgumentException('Email required');
        }

        if (mb_strlen($password) < 8) {
            $errors[] = new InvalidArgumentException('Password too short');
        }

        // Should not throw
        raise($errors);

        expect($errors)->toBeEmpty();
    });

    test('exception group with context', function (): void {
        try {
            $group = new ExceptionGroup('Validation failed', [
                new InvalidArgumentException('Error 1'),
                new InvalidArgumentException('Error 2'),
            ]);

            $group->withContext(['user_id' => 123, 'action' => 'registration']);
            $group->withTags(['validation', 'user-input']);

            throw $group;
        } catch (ExceptionGroup $exceptionGroup) {
            expect($exceptionGroup->getContext())->toBe(['user_id' => 123, 'action' => 'registration'])
                ->and($exceptionGroup->getTags())->toBe(['validation', 'user-input']);

            return;
        }

        throw new Exception('Expected ExceptionGroup to be thrown');
    });
});
