<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Throw\Support\HttpStatusCode;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Exceptions\TestExpectationFailedException;
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

    test('accepts callable condition', function (): void {
        expect(fn () => TestException::withMessage('Callback failed')->throwIf(fn (): true => true))
            ->toThrow(TestException::class, 'Callback failed');
    });

    test('evaluates callable lazily', function (): void {
        $called = false;
        $callable = function () use (&$called): true {
            $called = true;

            return true;
        };

        expect(fn () => TestException::withMessage('Callback failed')->throwIf($callable))
            ->toThrow(TestException::class, 'Callback failed');

        expect($called)->toBeTrue();
    });

    test('callable returning false does not throw', function (): void {
        TestException::withMessage('Should not throw')->throwIf(fn (): false => false);

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

    test('accepts callable condition', function (): void {
        expect(fn () => TestException::withMessage('Callback failed')->throwUnless(fn (): false => false))
            ->toThrow(TestException::class, 'Callback failed');
    });

    test('evaluates callable lazily', function (): void {
        $called = false;
        $callable = function () use (&$called): false {
            $called = true;

            return false;
        };

        expect(fn () => TestException::withMessage('Callback failed')->throwUnless($callable))
            ->toThrow(TestException::class, 'Callback failed');

        expect($called)->toBeTrue();
    });

    test('callable returning true does not throw', function (): void {
        TestException::withMessage('Should not throw')->throwUnless(fn (): true => true);

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
            TestException::withMessage('Not found')->abortIf(true, HttpStatusCode::NotFound);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(404);
            expect($httpException->getMessage())->toBe('Not found');

            return;
        }

        throw TestExpectationFailedException::expectedThrow(HttpException::class);
    });

    test('does not abort when condition is false', function (): void {
        $exception = TestException::withMessage('Server error');
        $exception->abortIf(false);

        expect(true)->toBeTrue();
    });

    test('aborts with 401 for authentication failure', function (): void {
        try {
            TestException::withMessage('Unauthorized')->abortIf(true, HttpStatusCode::Unauthorized);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(401);
            expect($httpException->getMessage())->toBe('Unauthorized');

            return;
        }

        throw TestExpectationFailedException::expectedThrow(HttpException::class);
    });

    test('accepts callable condition', function (): void {
        expect(fn () => TestException::withMessage('Rate limited')->abortIf(fn (): true => true, HttpStatusCode::TooManyRequests))
            ->toThrow(HttpException::class);
    });

    test('evaluates callable lazily', function (): void {
        $called = false;
        $callable = function () use (&$called): true {
            $called = true;

            return true;
        };

        try {
            TestException::withMessage('Rate limited')->abortIf($callable, HttpStatusCode::TooManyRequests);
        } catch (HttpException $httpException) {
            expect($called)->toBeTrue()
                ->and($httpException->getStatusCode())->toBe(429);

            return;
        }

        throw TestExpectationFailedException::expectedThrow(HttpException::class);
    });

    test('callable returning false does not abort', function (): void {
        TestException::withMessage('Should not abort')->abortIf(fn (): false => false, HttpStatusCode::InternalServerError);

        expect(true)->toBeTrue();
    });
});

describe('abortUnless', function (): void {
    test('aborts with default 500 status when condition is false', function (): void {
        expect(fn () => TestException::withMessage('Server error')->abortUnless(false))
            ->toThrow(HttpException::class);
    });

    test('aborts with custom status code when condition is false', function (): void {
        try {
            TestException::withMessage('Forbidden')->abortUnless(false, HttpStatusCode::Forbidden);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403);
            expect($httpException->getMessage())->toBe('Forbidden');

            return;
        }

        throw TestExpectationFailedException::expectedThrow(HttpException::class);
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
            TestException::withMessage('Missing permission')->abortUnless($user->canAdmin, HttpStatusCode::Forbidden);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403);
            expect($httpException->getMessage())->toBe('Missing permission');

            return;
        }

        throw TestExpectationFailedException::expectedThrow(HttpException::class);
    });

    test('accepts callable condition', function (): void {
        expect(fn () => TestException::withMessage('Authentication required')->abortUnless(fn (): false => false, HttpStatusCode::Unauthorized))
            ->toThrow(HttpException::class);
    });

    test('evaluates callable lazily', function (): void {
        $called = false;
        $callable = function () use (&$called): false {
            $called = true;

            return false;
        };

        try {
            TestException::withMessage('Authentication required')->abortUnless($callable, HttpStatusCode::Unauthorized);
        } catch (HttpException $httpException) {
            expect($called)->toBeTrue()
                ->and($httpException->getStatusCode())->toBe(401);

            return;
        }

        throw TestExpectationFailedException::expectedThrow(HttpException::class);
    });

    test('callable returning true does not abort', function (): void {
        TestException::withMessage('Should not abort')->abortUnless(fn (): true => true, HttpStatusCode::InternalServerError);

        expect(true)->toBeTrue();
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

        throw TestExpectationFailedException::expectedThrow(TestException::class);
    });
});
