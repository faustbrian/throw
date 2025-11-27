<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Throw\Support\Assertion;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Fixtures\TestDomainException;

use function Cline\Throw\ensure;

describe('ensure helper', function (): void {
    test('returns Assertion instance', function (): void {
        $assertion = ensure(true);

        expect($assertion)->toBeInstanceOf(Assertion::class);
    });

    test('accepts boolean condition', function (): void {
        $assertion = ensure(1 === 1);

        expect($assertion)->toBeInstanceOf(Assertion::class);
    });
});

describe('orThrow with exception class', function (): void {
    test('throws exception when condition is false', function (): void {
        expect(fn () => ensure(false)->orThrow(RuntimeException::class))
            ->toThrow(RuntimeException::class);
    });

    test('does not throw when condition is true', function (): void {
        ensure(true)->orThrow(RuntimeException::class);

        expect(true)->toBeTrue();
    });

    test('throws with custom message', function (): void {
        expect(fn () => ensure(false)->orThrow(RuntimeException::class, 'Custom error'))
            ->toThrow(RuntimeException::class, 'Custom error');
    });

    test('throws domain exception', function (): void {
        expect(fn () => ensure(false)->orThrow(TestDomainException::class, 'Domain error'))
            ->toThrow(TestDomainException::class, 'Domain error');
    });
});

describe('orThrow with exception instance', function (): void {
    test('throws exception instance when condition is false', function (): void {
        $exception = TestDomainException::withMessage('Instance error');

        expect(fn () => ensure(false)->orThrow($exception))
            ->toThrow(TestDomainException::class, 'Instance error');
    });

    test('does not throw when condition is true', function (): void {
        $exception = TestDomainException::withMessage('Instance error');

        ensure(true)->orThrow($exception);

        expect(true)->toBeTrue();
    });

    test('throws exception with context', function (): void {
        $exception = TestDomainException::withMessage('Context error')
            ->withContext(['user_id' => 123]);

        try {
            ensure(false)->orThrow($exception);
        } catch (TestDomainException $testDomainException) {
            expect($testDomainException->getMessage())->toBe('Context error')
                ->and($testDomainException->getContext())->toBe(['user_id' => 123]);

            return;
        }

        throw new Exception('Expected TestDomainException to be thrown');
    });

    test('preserves factory method creation', function (): void {
        expect(fn () => ensure(false)->orThrow(TestDomainException::businessRuleViolation()))
            ->toThrow(TestDomainException::class, 'Business rule violated');
    });
});

describe('orAbort', function (): void {
    test('aborts when condition is false', function (): void {
        expect(fn () => ensure(false)->orAbort(404))
            ->toThrow(HttpException::class);
    });

    test('does not abort when condition is true', function (): void {
        ensure(true)->orAbort(404);

        expect(true)->toBeTrue();
    });

    test('aborts with correct status code', function (): void {
        try {
            ensure(false)->orAbort(403);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('aborts with custom message', function (): void {
        try {
            ensure(false)->orAbort(404, 'Resource not found');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(404)
                ->and($httpException->getMessage())->toBe('Resource not found');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('aborts with 401 status', function (): void {
        try {
            ensure(false)->orAbort(401, 'Unauthorized');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(401)
                ->and($httpException->getMessage())->toBe('Unauthorized');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('aborts with 500 status', function (): void {
        try {
            ensure(false)->orAbort(500, 'Internal server error');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(500)
                ->and($httpException->getMessage())->toBe('Internal server error');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });
});

describe('real-world usage', function (): void {
    test('guards against null values', function (): void {
        $user = null;

        expect(fn () => ensure($user !== null)->orThrow(RuntimeException::class, 'User not found'))
            ->toThrow(RuntimeException::class, 'User not found');
    });

    test('validates type checks', function (): void {
        $value = 'string';

        expect(fn () => ensure(is_array($value))->orThrow(RuntimeException::class, 'Expected array'))
            ->toThrow(RuntimeException::class, 'Expected array');
    });

    test('checks permissions', function (): void {
        $isAdmin = false;

        expect(fn () => ensure($isAdmin)->orAbort(403, 'Forbidden'))
            ->toThrow(HttpException::class);
    });

    test('validates resource existence', function (): void {
        $resource = null;

        expect(fn () => ensure($resource !== null)->orAbort(404, 'Not found'))
            ->toThrow(HttpException::class);
    });

    test('allows valid state to pass through', function (): void {
        $user = new stdClass();
        $user->id = 123;

        ensure($user instanceof stdClass)->orThrow(RuntimeException::class, 'User required');
        ensure($user->id > 0)->orThrow(RuntimeException::class, 'Invalid user ID');

        expect($user->id)->toBe(123);
    });

    test('chains multiple assertions', function (): void {
        $email = 'test@example.com';

        ensure($email !== null)->orThrow(RuntimeException::class, 'Email required');
        ensure(str_contains($email, '@'))->orThrow(RuntimeException::class, 'Invalid email');

        expect($email)->toBe('test@example.com');
    });
});
