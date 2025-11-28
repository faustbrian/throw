<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Throw\Support\Errors;
use Tests\Fixtures\TestDomainException;
use Tests\Fixtures\TestInfrastructureException;

describe('Errors::is', function (): void {
    test('returns true when exception matches type', function (): void {
        $exception = new RuntimeException('Error');

        expect(Errors::is($exception, RuntimeException::class))->toBeTrue();
    });

    test('returns false when exception does not match type', function (): void {
        $exception = new RuntimeException('Error');

        expect(Errors::is($exception, InvalidArgumentException::class))->toBeFalse();
    });

    test('checks previous exception in chain', function (): void {
        $previous = new InvalidArgumentException('Previous error');
        $exception = new RuntimeException('Error', 0, $previous);

        expect(Errors::is($exception, InvalidArgumentException::class))->toBeTrue();
    });

    test('checks entire exception chain', function (): void {
        $root = new LogicException('Root error');
        $middle = new InvalidArgumentException('Middle error', 0, $root);
        $exception = new RuntimeException('Error', 0, $middle);

        expect(Errors::is($exception, LogicException::class))->toBeTrue();
    });

    test('returns false when type not in chain', function (): void {
        $previous = new LogicException('Previous error');
        $exception = new RuntimeException('Error', 0, $previous);

        expect(Errors::is($exception, InvalidArgumentException::class))->toBeFalse();
    });

    test('works with domain exceptions', function (): void {
        $exception = TestDomainException::withMessage('Domain error');

        expect(Errors::is($exception, TestDomainException::class))->toBeTrue();
    });

    test('checks wrapped domain exceptions', function (): void {
        $previous = TestDomainException::withMessage('Domain error');
        $exception = new RuntimeException('Wrapper', 0, $previous);

        expect(Errors::is($exception, TestDomainException::class))->toBeTrue();
    });

    test('checks parent class types', function (): void {
        $exception = new InvalidArgumentException('Error');

        expect(Errors::is($exception, Exception::class))->toBeTrue();
    });
});

describe('Errors::as', function (): void {
    test('returns exception when type matches', function (): void {
        $exception = new RuntimeException('Error');

        $result = Errors::as($exception, RuntimeException::class);

        expect($result)->toBeInstanceOf(RuntimeException::class)
            ->and($result?->getMessage())->toBe('Error');
    });

    test('returns null when type does not match', function (): void {
        $exception = new RuntimeException('Error');

        $result = Errors::as($exception, InvalidArgumentException::class);

        expect($result)->toBeNull();
    });

    test('returns exception from chain', function (): void {
        $previous = new InvalidArgumentException('Previous error');
        $exception = new RuntimeException('Error', 0, $previous);

        $result = Errors::as($exception, InvalidArgumentException::class);

        expect($result)->toBeInstanceOf(InvalidArgumentException::class)
            ->and($result?->getMessage())->toBe('Previous error');
    });

    test('returns first matching exception in chain', function (): void {
        $root = new InvalidArgumentException('Root error');
        $middle = new RuntimeException('Middle error', 0, $root);
        $exception = new RuntimeException('Top error', 0, $middle);

        $result = Errors::as($exception, RuntimeException::class);

        expect($result)->toBeInstanceOf(RuntimeException::class)
            ->and($result?->getMessage())->toBe('Top error');
    });

    test('returns null when type not in chain', function (): void {
        $previous = new LogicException('Previous error');
        $exception = new RuntimeException('Error', 0, $previous);

        $result = Errors::as($exception, InvalidArgumentException::class);

        expect($result)->toBeNull();
    });

    test('works with domain exceptions', function (): void {
        $exception = TestDomainException::withMessage('Domain error');

        $result = Errors::as($exception, TestDomainException::class);

        expect($result)->toBeInstanceOf(TestDomainException::class)
            ->and($result?->getMessage())->toBe('Domain error');
    });

    test('extracts wrapped domain exception', function (): void {
        $previous = TestDomainException::withMessage('Domain error');
        $exception = new RuntimeException('Wrapper', 0, $previous);

        $result = Errors::as($exception, TestDomainException::class);

        expect($result)->toBeInstanceOf(TestDomainException::class)
            ->and($result?->getMessage())->toBe('Domain error');
    });

    test('preserves exception context when extracted', function (): void {
        $domainException = TestDomainException::withMessage('Domain error')
            ->withContext(['user_id' => 123]);

        $wrapper = new RuntimeException('Wrapper', 0, $domainException);

        $result = Errors::as($wrapper, TestDomainException::class);

        expect($result)->toBeInstanceOf(TestDomainException::class)
            ->and($result?->getContext())->toBe(['user_id' => 123]);
    });

    test('casts to parent class', function (): void {
        $exception = new InvalidArgumentException('Error');

        $result = Errors::as($exception, Exception::class);

        expect($result)->toBeInstanceOf(Exception::class)
            ->and($result)->toBeInstanceOf(InvalidArgumentException::class);
    });
});

describe('real-world usage', function (): void {
    test('handles wrapped database exceptions', function (): void {
        $pdoException = new PDOException('Connection failed');
        $exception = new RuntimeException('Database error', 0, $pdoException);

        expect(Errors::is($exception, PDOException::class))->toBeTrue();

        $pdo = Errors::as($exception, PDOException::class);
        expect($pdo)->toBeInstanceOf(PDOException::class);
    });

    test('extracts specific error for logging', function (): void {
        $domainError = TestDomainException::withMessage('Business rule violated')
            ->withContext(['rule' => 'insufficient_balance', 'required' => 100]);

        $wrapper = new RuntimeException('Payment failed', 0, $domainError);

        if (Errors::is($wrapper, TestDomainException::class)) {
            $domain = Errors::as($wrapper, TestDomainException::class);

            expect($domain?->getContext())->toBe([
                'rule' => 'insufficient_balance',
                'required' => 100,
            ]);
        }
    });

    test('handles infrastructure vs domain errors differently', function (): void {
        $infrastructureError = TestInfrastructureException::withMessage('API call failed');
        $wrapper = new RuntimeException('Operation failed', 0, $infrastructureError);

        $isInfrastructure = Errors::is($wrapper, TestInfrastructureException::class);
        $isDomain = Errors::is($wrapper, TestDomainException::class);

        expect($isInfrastructure)->toBeTrue()
            ->and($isDomain)->toBeFalse();
    });

    test('safely checks for specific errors in catch blocks', function (): void {
        try {
            throw new InvalidArgumentException('Invalid input');
        } catch (Throwable $throwable) {
            if (Errors::is($throwable, InvalidArgumentException::class)) {
                expect($throwable->getMessage())->toBe('Invalid input');

                return;
            }

            throw new Exception('Should have matched InvalidArgumentException', $throwable->getCode(), $throwable);
        }
    });

    test('extracts and uses specific exception types', function (): void {
        $original = new InvalidArgumentException('Bad value', 42);
        $wrapper = new RuntimeException('Validation failed', 0, $original);

        $invalid = Errors::as($wrapper, InvalidArgumentException::class);

        expect($invalid)->not->toBeNull()
            ->and($invalid?->getCode())->toBe(42)
            ->and($invalid?->getMessage())->toBe('Bad value');
    });
});
