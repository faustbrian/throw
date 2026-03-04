<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\Exceptions\TestExpectationFailedException;
use Tests\Fixtures\TestDomainException;
use Tests\Fixtures\TestInfrastructureException;

describe('wrap', function (): void {
    test('wraps exception and sets it as previous', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')->wrap($original);

        expect($wrapped->getWrapped())->toBe($original)
            ->and($wrapped->getPrevious())->toBe($original);
    });

    test('preserves original message', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')->wrap($original);

        expect($wrapped->getMessage())->toBe('Wrapped error')
            ->and($original->getMessage())->toBe('Original error');
    });

    test('creates new exception instance', function (): void {
        $original = new RuntimeException('Original error');
        $exception = TestDomainException::withMessage('Wrapped error');
        $wrapped = $exception->wrap($original);

        expect($wrapped)->not->toBe($exception)
            ->and($wrapped)->toBeInstanceOf(TestDomainException::class);
    });

    test('preserves context when wrapping', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')
            ->withContext(['user_id' => 123])
            ->wrap($original);

        expect($wrapped->getContext())->toBe(['user_id' => 123]);
    });

    test('preserves tags when wrapping', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')
            ->withTags(['critical'])
            ->wrap($original);

        expect($wrapped->getTags())->toBe(['critical']);
    });

    test('preserves metadata when wrapping', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')
            ->withMetadata(['debug' => 'info'])
            ->wrap($original);

        expect($wrapped->getMetadata())->toBe(['debug' => 'info']);
    });

    test('preserves all context data when wrapping', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')
            ->withContext(['user_id' => 123])
            ->withTags(['critical'])
            ->withMetadata(['debug' => 'info'])
            ->wrap($original);

        expect($wrapped->getContext())->toBe(['user_id' => 123])
            ->and($wrapped->getTags())->toBe(['critical'])
            ->and($wrapped->getMetadata())->toBe(['debug' => 'info']);
    });

    test('wraps PDOException', function (): void {
        $original = new PDOException('SQLSTATE[HY000]: General error');
        $wrapped = TestInfrastructureException::withMessage('Database query failed')->wrap($original);

        expect($wrapped->getWrapped())->toBe($original)
            ->and($wrapped->getPrevious())->toBe($original)
            ->and($wrapped->getMessage())->toBe('Database query failed');
    });

    test('wraps InvalidArgumentException', function (): void {
        $original = new InvalidArgumentException('Invalid argument provided');
        $wrapped = TestDomainException::withMessage('Invalid input')->wrap($original);

        expect($wrapped->getWrapped())->toBe($original)
            ->and($wrapped->getPrevious())->toBe($original);
    });

    test('can chain wrap with other methods', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')
            ->wrap($original)
            ->withContext(['operation' => 'payment'])
            ->withTags(['financial']);

        expect($wrapped->getWrapped())->toBe($original)
            ->and($wrapped->getContext())->toBe(['operation' => 'payment'])
            ->and($wrapped->getTags())->toBe(['financial']);
    });
});

describe('getWrapped', function (): void {
    test('returns wrapped exception', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')->wrap($original);

        expect($wrapped->getWrapped())->toBe($original);
    });

    test('returns null when no exception is wrapped', function (): void {
        $exception = TestDomainException::withMessage('Not wrapped');

        expect($exception->getWrapped())->toBeNull();
    });
});

describe('hasWrapped', function (): void {
    test('returns true when exception is wrapped', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped error')->wrap($original);

        expect($wrapped->hasWrapped())->toBeTrue();
    });

    test('returns false when no exception is wrapped', function (): void {
        $exception = TestDomainException::withMessage('Not wrapped');

        expect($exception->hasWrapped())->toBeFalse();
    });
});

describe('error chain', function (): void {
    test('maintains exception chain through getPrevious', function (): void {
        $original = new RuntimeException('Level 1');
        $middle = new InvalidArgumentException('Level 2', 0, $original);
        $wrapped = TestDomainException::withMessage('Level 3')->wrap($middle);

        expect($wrapped->getPrevious())->toBe($middle)
            ->and($wrapped->getPrevious()?->getPrevious())->toBe($original);
    });

    test('can throw wrapped exception', function (): void {
        $original = new RuntimeException('Original error');

        try {
            throw TestDomainException::withMessage('Wrapped error')->wrap($original);
        } catch (TestDomainException $testDomainException) {
            expect($testDomainException->getWrapped())->toBe($original)
                ->and($testDomainException->getMessage())->toBe('Wrapped error');

            return;
        }

        throw TestExpectationFailedException::expectedThrow(TestDomainException::class);
    });

    test('wrapped exception can be used in catch blocks', function (): void {
        $original = new PDOException('Database error');
        $wrapped = TestInfrastructureException::withMessage('Query failed')->wrap($original);

        try {
            throw $wrapped;
        } catch (TestInfrastructureException $testInfrastructureException) {
            expect($testInfrastructureException->getWrapped())->toBeInstanceOf(PDOException::class)
                ->and($testInfrastructureException->getWrapped()?->getMessage())->toBe('Database error');

            return;
        }

        throw TestExpectationFailedException::expectedThrow(TestInfrastructureException::class);
    });
});
