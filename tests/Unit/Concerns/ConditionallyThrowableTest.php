<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Fixtures\TestException;

describe('throwIf', function (): void {
    test('throws exception when condition is true', function (): void {
        expect(fn () => TestException::withMessage('Test error')->throwIf(true))
            ->toThrow(TestException::class, 'Test error');
    });

    test('does not throw when condition is false', function (): void {
        $exception = TestException::withMessage('Test error');
        $exception->throwIf(false);

        expect(true)->toBeTrue();
    });

    test('evaluates complex conditions correctly', function (): void {
        $value = null;

        expect(fn () => TestException::withMessage('Value is null')->throwIf($value === null))
            ->toThrow(TestException::class, 'Value is null');
    });

    test('does not throw when complex condition is false', function (): void {
        $value = 'not null';

        $exception = TestException::withMessage('Value is null');
        $exception->throwIf($value === null);

        expect(true)->toBeTrue();
    });
});

describe('throwUnless', function (): void {
    test('throws exception when condition is false', function (): void {
        expect(fn () => TestException::withMessage('Test error')->throwUnless(false))
            ->toThrow(TestException::class, 'Test error');
    });

    test('does not throw when condition is true', function (): void {
        $exception = TestException::withMessage('Test error');
        $exception->throwUnless(true);

        expect(true)->toBeTrue();
    });

    test('evaluates interface checks correctly', function (): void {
        $object = new stdClass();

        expect(fn () => TestException::withMessage('Must implement interface')->throwUnless($object instanceof DateTimeInterface))
            ->toThrow(TestException::class, 'Must implement interface');
    });

    test('does not throw when interface check passes', function (): void {
        $object = Date::now();

        $exception = TestException::withMessage('Must implement interface');
        $exception->throwUnless($object instanceof DateTimeInterface);

        expect(true)->toBeTrue();
    });
});

describe('abortIf', function (): void {
    test('aborts with default 500 status when condition is true', function (): void {
        expect(fn () => TestException::withMessage('Server error')->abortIf(true))
            ->toThrow(HttpException::class);
    });

    test('aborts with custom status code when condition is true', function (): void {
        try {
            TestException::withMessage('Not found')->abortIf(true, 404);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(404);
            expect($httpException->getMessage())->toBe('Not found');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('does not abort when condition is false', function (): void {
        $exception = TestException::withMessage('Server error');
        $exception->abortIf(false);

        expect(true)->toBeTrue();
    });

    test('aborts with 401 for authentication failure', function (): void {
        try {
            TestException::withMessage('Unauthorized')->abortIf(true, 401);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(401);
            expect($httpException->getMessage())->toBe('Unauthorized');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });
});

describe('abortUnless', function (): void {
    test('aborts with default 500 status when condition is false', function (): void {
        expect(fn () => TestException::withMessage('Server error')->abortUnless(false))
            ->toThrow(HttpException::class);
    });

    test('aborts with custom status code when condition is false', function (): void {
        try {
            TestException::withMessage('Forbidden')->abortUnless(false, 403);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403);
            expect($httpException->getMessage())->toBe('Forbidden');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('does not abort when condition is true', function (): void {
        $exception = TestException::withMessage('Server error');
        $exception->abortUnless(true);

        expect(true)->toBeTrue();
    });

    test('aborts with 403 for authorization failure', function (): void {
        $user = new stdClass();
        $user->canAdmin = false;

        try {
            TestException::withMessage('Missing permission')->abortUnless($user->canAdmin, 403);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403);
            expect($httpException->getMessage())->toBe('Missing permission');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });
});

describe('fluent chaining', function (): void {
    test('chains with static factory methods naturally', function (): void {
        expect(fn () => TestException::withMessage('Fluent error')->throwIf(true))
            ->toThrow(TestException::class, 'Fluent error');
    });

    test('supports multiple condition checks with same exception instance', function (): void {
        $exception = TestException::withMessage('Reusable exception');

        $exception->throwIf(false);
        $exception->throwUnless(true);

        expect(true)->toBeTrue();
    });

    test('preserves exception message through chain', function (): void {
        try {
            TestException::withMessage('Original message')->throwIf(true);
        } catch (TestException $testException) {
            expect($testException->getMessage())->toBe('Original message');

            return;
        }

        throw new Exception('Expected TestException to be thrown');
    });
});
