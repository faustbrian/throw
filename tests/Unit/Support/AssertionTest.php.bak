<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Throw\Support\Assertion;
use Cline\Throw\Support\HttpStatusCode;
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

    test('accepts callable condition', function (): void {
        $assertion = ensure(fn (): true => true);

        expect($assertion)->toBeInstanceOf(Assertion::class);
    });

    test('evaluates callable lazily', function (): void {
        $called = false;
        $callable = function () use (&$called): true {
            $called = true;

            return true;
        };

        $assertion = ensure($callable);

        expect($called)->toBeTrue()
            ->and($assertion)->toBeInstanceOf(Assertion::class);
    });

    test('callable returning false triggers orThrow', function (): void {
        expect(fn () => ensure(fn (): false => false)->orThrow(RuntimeException::class, 'Callback failed'))
            ->toThrow(RuntimeException::class, 'Callback failed');
    });

    test('callable returning true does not trigger orThrow', function (): void {
        ensure(fn (): true => true)->orThrow(RuntimeException::class);

        expect(true)->toBeTrue();
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

describe('throwIf and throwUnless', function (): void {
    test('throwIf throws when condition is true', function (): void {
        expect(fn () => ensure(true)->throwIf(RuntimeException::class, 'Error'))
            ->toThrow(RuntimeException::class, 'Error');
    });

    test('throwIf does not throw when condition is false', function (): void {
        ensure(false)->throwIf(RuntimeException::class, 'Error');

        expect(true)->toBeTrue();
    });

    test('throwUnless throws when condition is false', function (): void {
        expect(fn () => ensure(false)->throwUnless(RuntimeException::class, 'Error'))
            ->toThrow(RuntimeException::class, 'Error');
    });

    test('throwUnless does not throw when condition is true', function (): void {
        ensure(true)->throwUnless(RuntimeException::class, 'Error');

        expect(true)->toBeTrue();
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
        expect(fn () => ensure(false)->orAbort(HttpStatusCode::NotFound))
            ->toThrow(HttpException::class);
    });

    test('does not abort when condition is true', function (): void {
        ensure(true)->orAbort(HttpStatusCode::NotFound);

        expect(true)->toBeTrue();
    });

    test('aborts with correct status code', function (): void {
        try {
            ensure(false)->orAbort(HttpStatusCode::Forbidden);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('aborts with custom message', function (): void {
        try {
            ensure(false)->orAbort(HttpStatusCode::NotFound, 'Resource not found');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(404)
                ->and($httpException->getMessage())->toBe('Resource not found');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('aborts with 401 status', function (): void {
        try {
            ensure(false)->orAbort(HttpStatusCode::Unauthorized, 'Unauthorized');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(401)
                ->and($httpException->getMessage())->toBe('Unauthorized');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('aborts with 500 status', function (): void {
        try {
            ensure(false)->orAbort(HttpStatusCode::InternalServerError, 'Internal server error');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(500)
                ->and($httpException->getMessage())->toBe('Internal server error');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });
});

describe('abortIf and abortUnless', function (): void {
    test('abortIf aborts when condition is true', function (): void {
        try {
            ensure(true)->abortIf(HttpStatusCode::BadRequest, 'Error');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(400)
                ->and($httpException->getMessage())->toBe('Error');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('abortIf does not abort when condition is false', function (): void {
        ensure(false)->abortIf(HttpStatusCode::BadRequest, 'Error');

        expect(true)->toBeTrue();
    });

    test('abortUnless aborts when condition is false', function (): void {
        try {
            ensure(false)->abortUnless(HttpStatusCode::NotFound, 'Not found');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(404)
                ->and($httpException->getMessage())->toBe('Not found');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('abortUnless does not abort when condition is true', function (): void {
        ensure(true)->abortUnless(HttpStatusCode::NotFound, 'Not found');

        expect(true)->toBeTrue();
    });
});

describe('HTTP status code helpers', function (): void {
    test('orBadRequest aborts with 400', function (): void {
        try {
            ensure(false)->orBadRequest('Invalid input');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(400)
                ->and($httpException->getMessage())->toBe('Invalid input');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orUnauthorized aborts with 401', function (): void {
        try {
            ensure(false)->orUnauthorized('Authentication required');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(401)
                ->and($httpException->getMessage())->toBe('Authentication required');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orForbidden aborts with 403', function (): void {
        try {
            ensure(false)->orForbidden('Access denied');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403)
                ->and($httpException->getMessage())->toBe('Access denied');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orNotFound aborts with 404', function (): void {
        try {
            ensure(false)->orNotFound('Resource not found');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(404)
                ->and($httpException->getMessage())->toBe('Resource not found');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orConflict aborts with 409', function (): void {
        try {
            ensure(false)->orConflict('Resource already exists');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(409)
                ->and($httpException->getMessage())->toBe('Resource already exists');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orUnprocessable aborts with 422', function (): void {
        try {
            ensure(false)->orUnprocessable('Validation failed');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(422)
                ->and($httpException->getMessage())->toBe('Validation failed');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orTooManyRequests aborts with 429', function (): void {
        try {
            ensure(false)->orTooManyRequests('Rate limit exceeded');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(429)
                ->and($httpException->getMessage())->toBe('Rate limit exceeded');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orServerError aborts with 500', function (): void {
        try {
            ensure(false)->orServerError('Internal server error');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(500)
                ->and($httpException->getMessage())->toBe('Internal server error');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orServiceUnavailable aborts with 503', function (): void {
        try {
            ensure(false)->orServiceUnavailable('Service unavailable');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(503)
                ->and($httpException->getMessage())->toBe('Service unavailable');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('HTTP helpers do not abort when condition is true', function (): void {
        ensure(true)->orBadRequest();
        ensure(true)->orUnauthorized();
        ensure(true)->orForbidden();
        ensure(true)->orNotFound();
        ensure(true)->orConflict();
        ensure(true)->orUnprocessable();
        ensure(true)->orTooManyRequests();
        ensure(true)->orServerError();
        ensure(true)->orServiceUnavailable();
        ensure(true)->orMethodNotAllowed();
        ensure(true)->orNotAcceptable();
        ensure(true)->orRequestTimeout();
        ensure(true)->orGone();
        ensure(true)->orPayloadTooLarge();
        ensure(true)->orUnsupportedMediaType();
        ensure(true)->orImATeapot();
        ensure(true)->orLocked();
        ensure(true)->orPreconditionRequired();
        ensure(true)->orNotImplemented();
        ensure(true)->orBadGateway();
        ensure(true)->orGatewayTimeout();

        expect(true)->toBeTrue();
    });

    test('additional HTTP helpers abort with correct codes', function (): void {
        try {
            ensure(false)->orMethodNotAllowed();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(405);
        }

        try {
            ensure(false)->orNotAcceptable();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(406);
        }

        try {
            ensure(false)->orRequestTimeout();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(408);
        }

        try {
            ensure(false)->orGone();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(410);
        }

        try {
            ensure(false)->orPayloadTooLarge();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(413);
        }

        try {
            ensure(false)->orUnsupportedMediaType();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(415);
        }

        try {
            ensure(false)->orImATeapot();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(418);
        }

        try {
            ensure(false)->orLocked();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(423);
        }

        try {
            ensure(false)->orPreconditionRequired();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(428);
        }

        try {
            ensure(false)->orNotImplemented();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(501);
        }

        try {
            ensure(false)->orBadGateway();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(502);
        }

        try {
            ensure(false)->orGatewayTimeout();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(504);
        }
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

        expect(fn () => ensure($isAdmin)->orAbort(HttpStatusCode::Forbidden, 'Forbidden'))
            ->toThrow(HttpException::class);
    });

    test('validates resource existence', function (): void {
        $resource = null;

        expect(fn () => ensure($resource !== null)->orAbort(HttpStatusCode::NotFound, 'Not found'))
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

    test('uses HTTP status helpers', function (): void {
        $user = null;

        expect(fn () => ensure($user !== null)->orNotFound())
            ->toThrow(HttpException::class);
    });

    test('guards API authentication', function (): void {
        $token = null;

        expect(fn () => ensure($token !== null)->orUnauthorized('API token required'))
            ->toThrow(HttpException::class);
    });

    test('validates rate limiting', function (): void {
        $allowed = false;

        expect(fn () => ensure($allowed)->orTooManyRequests())
            ->toThrow(HttpException::class);
    });
});
