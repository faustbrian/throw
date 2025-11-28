<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Throw\Exceptions\DomainException;
use Cline\Throw\Exceptions\InfrastructureException;
use Cline\Throw\Exceptions\ValidationException;
use Tests\Fixtures\TestDomainException;
use Tests\Fixtures\TestInfrastructureException;
use Tests\Fixtures\TestValidationException;

describe('DomainException', function (): void {
    test('can be instantiated', function (): void {
        $exception = TestDomainException::withMessage('Business error');

        expect($exception)
            ->toBeInstanceOf(DomainException::class)
            ->toBeInstanceOf(\DomainException::class);
    });

    test('has conditionally throwable trait', function (): void {
        expect(fn () => TestDomainException::businessRuleViolation()->throwIf(true))
            ->toThrow(TestDomainException::class, 'Business rule violated');
    });

    test('has error context trait', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withContext(['user_id' => 123])
            ->withTags(['critical'])
            ->withMetadata(['debug' => 'info']);

        expect($exception->getContext())->toBe(['user_id' => 123])
            ->and($exception->getTags())->toBe(['critical'])
            ->and($exception->getMetadata())->toBe(['debug' => 'info']);
    });

    test('has wraps errors trait', function (): void {
        $original = new RuntimeException('Original error');
        $wrapped = TestDomainException::withMessage('Wrapped')->wrap($original);

        expect($wrapped->getWrapped())->toBe($original)
            ->and($wrapped->hasWrapped())->toBeTrue()
            ->and($wrapped->getPrevious())->toBe($original);
    });
});

describe('InfrastructureException', function (): void {
    test('can be instantiated', function (): void {
        $exception = TestInfrastructureException::withMessage('Infrastructure error');

        expect($exception)
            ->toBeInstanceOf(InfrastructureException::class)
            ->toBeInstanceOf(RuntimeException::class);
    });

    test('has conditionally throwable trait', function (): void {
        expect(fn () => TestInfrastructureException::databaseFailure()->throwIf(true))
            ->toThrow(TestInfrastructureException::class, 'Database connection failed');
    });

    test('has error context trait', function (): void {
        $exception = TestInfrastructureException::withMessage('Test')
            ->withContext(['host' => 'localhost'])
            ->withTags(['database'])
            ->withMetadata(['query' => 'SELECT 1']);

        expect($exception->getContext())->toBe(['host' => 'localhost'])
            ->and($exception->getTags())->toBe(['database'])
            ->and($exception->getMetadata())->toBe(['query' => 'SELECT 1']);
    });

    test('has wraps errors trait', function (): void {
        $original = new PDOException('Connection failed');
        $wrapped = TestInfrastructureException::withMessage('Wrapped')->wrap($original);

        expect($wrapped->getWrapped())->toBe($original)
            ->and($wrapped->hasWrapped())->toBeTrue()
            ->and($wrapped->getPrevious())->toBe($original);
    });
});

describe('ValidationException', function (): void {
    test('can be instantiated', function (): void {
        $exception = TestValidationException::withMessage('Validation error');

        expect($exception)
            ->toBeInstanceOf(ValidationException::class)
            ->toBeInstanceOf(RuntimeException::class);
    });

    test('has conditionally throwable trait', function (): void {
        expect(fn () => TestValidationException::invalidEmail()->throwIf(true))
            ->toThrow(TestValidationException::class, 'Invalid email format');
    });

    test('has error context trait', function (): void {
        $exception = TestValidationException::withMessage('Test')
            ->withContext(['field' => 'email'])
            ->withTags(['validation'])
            ->withMetadata(['rules' => ['email', 'required']]);

        expect($exception->getContext())->toBe(['field' => 'email'])
            ->and($exception->getTags())->toBe(['validation'])
            ->and($exception->getMetadata())->toBe(['rules' => ['email', 'required']]);
    });

    test('has wraps errors trait', function (): void {
        $original = new InvalidArgumentException('Invalid input');
        $wrapped = TestValidationException::withMessage('Wrapped')->wrap($original);

        expect($wrapped->getWrapped())->toBe($original)
            ->and($wrapped->hasWrapped())->toBeTrue()
            ->and($wrapped->getPrevious())->toBe($original);
    });
});
