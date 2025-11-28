<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Monad\Option\None;
use Cline\Monad\Option\Some;
use Cline\Monad\Result\Err;
use Cline\Monad\Result\Ok;
use Cline\Throw\Support\Attempt;
use Cline\Throw\Support\HttpStatusCode;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Fixtures\TestDomainException;

use function Cline\Throw\attempt;

describe('attempt helper', function (): void {
    test('returns Attempt instance', function (): void {
        $try = attempt(fn (): true => true);

        expect($try)->toBeInstanceOf(Attempt::class);
    });

    test('executes closure successfully', function (): void {
        $result = attempt(fn (): string => 'success')->orThrow(RuntimeException::class);

        expect($result)->toBe('success');
    });

    test('executes closure that throws exception', function (): void {
        $try = attempt(fn () => throw new RuntimeException('Error'));

        expect(fn (): mixed => $try->orThrow(TestDomainException::class))
            ->toThrow(TestDomainException::class);
    });

    test('executes callable array', function (): void {
        $result = attempt(TestInvokableClass::staticMethod(...))->getOrElse(null);

        expect($result)->toBe('static result');
    });

    test('executes invokable class string', function (): void {
        $result = attempt(TestInvokableClass::class)->getOrElse(null);

        expect($result)->toBe('invoked');
    });

    test('executes object with invoke method', function (): void {
        $result = attempt(
            new TestInvokableClass(),
        )->getOrElse(null);

        expect($result)->toBe('invoked');
    });

    test('executes object with handle method', function (): void {
        $result = attempt(
            new TestHandleClass(),
        )->getOrElse(null);

        expect($result)->toBe('handled');
    });

    test('throws when class has no invoke or handle method', function (): void {
        expect(fn (): Attempt => attempt(TestNoMethodClass::class))
            ->toThrow(InvalidArgumentException::class);
    });

    test('throws when object has no invoke or handle method', function (): void {
        expect(fn (): Attempt => attempt(
            new TestNoMethodClass(),
        ))
            ->toThrow(InvalidArgumentException::class);
    });
});

describe('get', function (): void {
    test('returns result when execution succeeds', function (): void {
        $result = attempt(fn (): int => 42)->get();

        expect($result)->toBe(42);
    });

    test('throws original exception when execution fails', function (): void {
        expect(fn (): mixed => attempt(fn () => throw new RuntimeException('Original error'))->get())
            ->toThrow(RuntimeException::class, 'Original error');
    });

    test('preserves exception type', function (): void {
        expect(fn (): mixed => attempt(fn () => throw new TestDomainException('Domain error'))->get())
            ->toThrow(TestDomainException::class, 'Domain error');
    });
});

describe('orThrow', function (): void {
    test('returns result when execution succeeds', function (): void {
        $result = attempt(fn (): int => 42)->orThrow(RuntimeException::class);

        expect($result)->toBe(42);
    });

    test('throws exception when execution fails', function (): void {
        expect(fn (): mixed => attempt(fn () => throw new RuntimeException('Original error'))
            ->orThrow(TestDomainException::class))
            ->toThrow(TestDomainException::class, 'Original error');
    });

    test('throws with custom message', function (): void {
        expect(fn (): mixed => attempt(fn () => throw new RuntimeException('Original'))
            ->orThrow(TestDomainException::class, 'Custom message'))
            ->toThrow(TestDomainException::class, 'Custom message');
    });

    test('throws exception instance', function (): void {
        $exception = TestDomainException::withMessage('Instance error');

        expect(fn (): mixed => attempt(fn () => throw new RuntimeException())
            ->orThrow($exception))
            ->toThrow(TestDomainException::class, 'Instance error');
    });

    test('wraps original exception as previous', function (): void {
        try {
            attempt(fn () => throw new RuntimeException('Original'))
                ->orThrow(TestDomainException::class, 'Wrapped');
        } catch (TestDomainException $testDomainException) {
            expect($testDomainException->getMessage())->toBe('Wrapped')
                ->and($testDomainException->getPrevious())->toBeInstanceOf(RuntimeException::class)
                ->and($testDomainException->getPrevious()->getMessage())->toBe('Original');

            return;
        }

        throw new Exception('Expected TestDomainException to be thrown');
    });
});

describe('abort', function (): void {
    test('returns result when execution succeeds', function (): void {
        $result = attempt(fn (): string => 'success')->abort(HttpStatusCode::NotFound);

        expect($result)->toBe('success');
    });

    test('aborts when execution fails', function (): void {
        expect(fn (): mixed => attempt(fn () => throw new RuntimeException())
            ->abort(HttpStatusCode::NotFound))
            ->toThrow(HttpException::class);
    });

    test('aborts with correct status code', function (): void {
        try {
            attempt(fn () => throw new RuntimeException('Error'))
                ->abort(HttpStatusCode::Forbidden);
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('aborts with custom message', function (): void {
        try {
            attempt(fn () => throw new RuntimeException('Original'))
                ->abort(HttpStatusCode::NotFound, 'Custom message');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(404)
                ->and($httpException->getMessage())->toBe('Custom message');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('aborts with original exception message when no custom message', function (): void {
        try {
            attempt(fn () => throw new RuntimeException('Original error'))
                ->abort(HttpStatusCode::BadRequest);
        } catch (HttpException $httpException) {
            expect($httpException->getMessage())->toBe('Original error');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });
});

describe('toOption', function (): void {
    test('returns Some when execution succeeds', function (): void {
        $result = attempt(fn (): string => 'success')->toOption();

        expect($result)->toBeInstanceOf(Some::class)
            ->and($result->unwrap())->toBe('success');
    });

    test('returns None when execution fails', function (): void {
        $result = attempt(fn () => throw new RuntimeException())->toOption();

        expect($result)->toBeInstanceOf(None::class);
    });

    test('can chain with Option methods', function (): void {
        $result = attempt(fn (): int => 42)
            ->toOption()
            ->map(fn ($x): int => $x * 2)
            ->unwrapOr(0);

        expect($result)->toBe(84);
    });

    test('None unwraps to default', function (): void {
        $result = attempt(fn () => throw new RuntimeException())
            ->toOption()
            ->unwrapOr('fallback');

        expect($result)->toBe('fallback');
    });
});

describe('toResult', function (): void {
    test('returns Ok when execution succeeds', function (): void {
        $result = attempt(fn (): string => 'success')->toResult();

        expect($result)->toBeInstanceOf(Ok::class)
            ->and($result->unwrap())->toBe('success');
    });

    test('returns Err when execution fails', function (): void {
        $result = attempt(fn () => throw new RuntimeException('Error'))->toResult();

        expect($result)->toBeInstanceOf(Err::class)
            ->and($result->unwrapErr())->toBeInstanceOf(RuntimeException::class)
            ->and($result->unwrapErr()->getMessage())->toBe('Error');
    });

    test('can chain with Result methods', function (): void {
        $result = attempt(fn (): int => 42)
            ->toResult()
            ->map(fn ($x): int => $x * 2)
            ->unwrapOr(0);

        expect($result)->toBe(84);
    });

    test('Err unwraps to default', function (): void {
        $result = attempt(fn () => throw new RuntimeException('Error'))
            ->toResult()
            ->unwrapOr('fallback');

        expect($result)->toBe('fallback');
    });

    test('can map error with mapErr', function (): void {
        $result = attempt(fn () => throw new RuntimeException('Original'))
            ->toResult()
            ->mapErr(fn (Throwable $e): string => 'Mapped: '.$e->getMessage())
            ->unwrapErr();

        expect($result)->toBe('Mapped: Original');
    });

    test('preserves exception type in Err', function (): void {
        $result = attempt(fn () => throw new TestDomainException('Domain error'))
            ->toResult();

        expect($result->unwrapErr())->toBeInstanceOf(TestDomainException::class)
            ->and($result->unwrapErr()->getMessage())->toBe('Domain error');
    });

    test('can convert to Option from Result', function (): void {
        $okOption = attempt(fn (): int => 42)
            ->toResult()
            ->ok();

        expect($okOption)->toBeInstanceOf(Some::class)
            ->and($okOption->unwrap())->toBe(42);

        $errOption = attempt(fn () => throw new RuntimeException())
            ->toResult()
            ->ok();

        expect($errOption)->toBeInstanceOf(None::class);
    });
});

describe('getOrElse', function (): void {
    test('returns result when execution succeeds', function (): void {
        $result = attempt(fn (): int => 42)->getOrElse(0);

        expect($result)->toBe(42);
    });

    test('returns default when execution fails', function (): void {
        $result = attempt(fn () => throw new RuntimeException())->getOrElse('default value');

        expect($result)->toBe('default value');
    });

    test('returns default array', function (): void {
        $result = attempt(fn () => throw new RuntimeException())->getOrElse([]);

        expect($result)->toBe([]);
    });

    test('returns default object', function (): void {
        $default = new stdClass();
        $result = attempt(fn () => throw new RuntimeException())->getOrElse($default);

        expect($result)->toBe($default);
    });
});

describe('recover', function (): void {
    test('returns result when execution succeeds', function (): void {
        $result = attempt(fn (): string => 'success')
            ->recover(fn (): string => 'rescued');

        expect($result)->toBe('success');
    });

    test('executes rescue callback when execution fails', function (): void {
        $result = attempt(fn () => throw new RuntimeException('Error'))
            ->recover(fn (Throwable $throwable): string => 'rescued: '.$throwable->getMessage());

        expect($result)->toBe('rescued: Error');
    });

    test('passes exception to rescue callback', function (): void {
        $result = attempt(fn () => throw new TestDomainException('Domain error'))
            ->recover(function (Throwable $throwable): string {
                expect($throwable)->toBeInstanceOf(TestDomainException::class);

                return 'handled';
            });

        expect($result)->toBe('handled');
    });

    test('rescue can return different type', function (): void {
        $result = attempt(fn (): int => throw new RuntimeException())
            ->recover(fn (): array => []);

        expect($result)->toBe([]);
    });
});

describe('HTTP status helpers', function (): void {
    test('orBadRequest aborts with 400', function (): void {
        try {
            attempt(fn () => throw new RuntimeException('Invalid'))
                ->orBadRequest('Bad request');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(400)
                ->and($httpException->getMessage())->toBe('Bad request');

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orUnauthorized aborts with 401', function (): void {
        try {
            attempt(fn () => throw new RuntimeException())
                ->orUnauthorized('Unauthorized');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(401);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orForbidden aborts with 403', function (): void {
        try {
            attempt(fn () => throw new RuntimeException())
                ->orForbidden();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(403);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orNotFound aborts with 404', function (): void {
        try {
            attempt(fn () => throw new RuntimeException())
                ->orNotFound('Not found');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(404);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orConflict aborts with 409', function (): void {
        try {
            attempt(fn () => throw new RuntimeException())
                ->orConflict();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(409);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orUnprocessable aborts with 422', function (): void {
        try {
            attempt(fn () => throw new RuntimeException())
                ->orUnprocessable('Validation failed');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(422);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orTooManyRequests aborts with 429', function (): void {
        try {
            attempt(fn () => throw new RuntimeException())
                ->orTooManyRequests();
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(429);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('orServerError aborts with 500', function (): void {
        try {
            attempt(fn () => throw new RuntimeException())
                ->orServerError('Server error');
        } catch (HttpException $httpException) {
            expect($httpException->getStatusCode())->toBe(500);

            return;
        }

        throw new Exception('Expected HttpException to be thrown');
    });

    test('HTTP helpers return result when execution succeeds', function (): void {
        expect(attempt(fn (): int => 42)->orBadRequest())->toBe(42);
        expect(attempt(fn (): int => 42)->orUnauthorized())->toBe(42);
        expect(attempt(fn (): int => 42)->orForbidden())->toBe(42);
        expect(attempt(fn (): int => 42)->orNotFound())->toBe(42);
        expect(attempt(fn (): int => 42)->orConflict())->toBe(42);
        expect(attempt(fn (): int => 42)->orUnprocessable())->toBe(42);
        expect(attempt(fn (): int => 42)->orTooManyRequests())->toBe(42);
        expect(attempt(fn (): int => 42)->orServerError())->toBe(42);
    });
});

describe('real-world usage', function (): void {
    test('database query with default', function (): void {
        $user = attempt(fn () => throw new RuntimeException('Not found'))
            ->getOrElse(null);

        expect($user)->toBeNull();
    });

    test('API call with rescue', function (): void {
        $result = attempt(fn () => throw new RuntimeException('API down'))
            ->recover(fn (): array => ['cached' => true]);

        expect($result)->toBe(['cached' => true]);
    });

    test('file operation with Option', function (): void {
        $content = attempt(fn () => throw new RuntimeException('File not found'))
            ->toOption();

        expect($content)->toBeInstanceOf(None::class);
    });

    test('authentication with abort', function (): void {
        expect(fn (): mixed => attempt(fn () => throw new RuntimeException('Invalid token'))
            ->orUnauthorized('Authentication required'))
            ->toThrow(HttpException::class);
    });

    test('resource loading with throw', function (): void {
        expect(fn (): mixed => attempt(fn () => throw new RuntimeException('Resource missing'))
            ->orThrow(TestDomainException::class, 'Resource not found'))
            ->toThrow(TestDomainException::class, 'Resource not found');
    });

    test('chained operations', function (): void {
        $result = attempt(fn (): int => 42)
            ->getOrElse(0);

        expect($result)->toBe(42);

        $fallback = attempt(fn () => throw new RuntimeException())
            ->getOrElse(100);

        expect($fallback)->toBe(100);
    });

    test('complex type preservation', function (): void {
        $result = attempt(fn (): array => ['key' => 'value'])
            ->getOrElse([]);

        expect($result)->toBe(['key' => 'value']);
    });

    test('exception context preservation in rescue', function (): void {
        $result = attempt(fn () => throw new TestDomainException('Error'))
            ->recover(function (Throwable $throwable): string {
                expect($throwable)->toBeInstanceOf(TestDomainException::class);

                return 'handled: '.$throwable->getMessage();
            });

        expect($result)->toBe('handled: Error');
    });

    test('invokable action class', function (): void {
        $result = attempt(TestInvokableClass::class)->getOrElse(null);

        expect($result)->toBe('invoked');
    });

    test('handle method class', function (): void {
        $result = attempt(TestHandleClass::class)->getOrElse(null);

        expect($result)->toBe('handled');
    });

    test('static method callable', function (): void {
        $result = attempt(TestInvokableClass::staticMethod(...))
            ->getOrElse(null);

        expect($result)->toBe('static result');
    });
});

// Test helper classes
/**
 * @author Brian Faust <brian@cline.sh>
 */
final class TestInvokableClass
{
    public function __invoke(): string
    {
        return 'invoked';
    }

    public static function staticMethod(): string
    {
        return 'static result';
    }
}

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class TestHandleClass
{
    public function handle(): string
    {
        return 'handled';
    }
}

/**
 * @author Brian Faust <brian@cline.sh>
 */
final class TestNoMethodClass
{
    public function someMethod(): string
    {
        return 'not invokable';
    }
}
