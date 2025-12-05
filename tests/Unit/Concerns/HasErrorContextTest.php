<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\Exceptions\TestExpectationFailedException;
use Tests\Fixtures\TestDomainException;

describe('withContext', function (): void {
    test('adds context to exception', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withContext(['user_id' => 123]);

        expect($exception->getContext())->toBe(['user_id' => 123]);
    });

    test('merges multiple context calls', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withContext(['user_id' => 123])
            ->withContext(['ip' => '127.0.0.1']);

        expect($exception->getContext())->toBe([
            'user_id' => 123,
            'ip' => '127.0.0.1',
        ]);
    });

    test('later context values override earlier ones', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withContext(['status' => 'pending'])
            ->withContext(['status' => 'active']);

        expect($exception->getContext())->toBe(['status' => 'active']);
    });

    test('returns same exception instance for fluent chaining', function (): void {
        $exception = TestDomainException::withMessage('Test');
        $result = $exception->withContext(['key' => 'value']);

        expect($result)->toBe($exception);
    });

    test('works with array values', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withContext(['tags' => ['payment', 'critical']]);

        expect($exception->getContext())->toBe(['tags' => ['payment', 'critical']]);
    });

    test('works with nested arrays', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withContext([
                'request' => [
                    'method' => 'POST',
                    'headers' => ['Content-Type' => 'application/json'],
                ],
            ]);

        expect($exception->getContext())->toBe([
            'request' => [
                'method' => 'POST',
                'headers' => ['Content-Type' => 'application/json'],
            ],
        ]);
    });
});

describe('withTags', function (): void {
    test('adds tags to exception', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withTags(['critical']);

        expect($exception->getTags())->toBe(['critical']);
    });

    test('merges multiple tag calls', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withTags(['critical'])
            ->withTags(['payment']);

        expect($exception->getTags())->toBe(['critical', 'payment']);
    });

    test('allows duplicate tags', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withTags(['critical'])
            ->withTags(['critical']);

        expect($exception->getTags())->toBe(['critical', 'critical']);
    });

    test('returns same exception instance for fluent chaining', function (): void {
        $exception = TestDomainException::withMessage('Test');
        $result = $exception->withTags(['critical']);

        expect($result)->toBe($exception);
    });

    test('works with multiple tags in one call', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withTags(['critical', 'payment', 'stripe']);

        expect($exception->getTags())->toBe(['critical', 'payment', 'stripe']);
    });
});

describe('withMetadata', function (): void {
    test('adds metadata to exception', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withMetadata(['debug' => 'info']);

        expect($exception->getMetadata())->toBe(['debug' => 'info']);
    });

    test('merges multiple metadata calls', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withMetadata(['request_id' => '123'])
            ->withMetadata(['duration_ms' => 150]);

        expect($exception->getMetadata())->toBe([
            'request_id' => '123',
            'duration_ms' => 150,
        ]);
    });

    test('later metadata values override earlier ones', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withMetadata(['version' => '1.0'])
            ->withMetadata(['version' => '2.0']);

        expect($exception->getMetadata())->toBe(['version' => '2.0']);
    });

    test('returns same exception instance for fluent chaining', function (): void {
        $exception = TestDomainException::withMessage('Test');
        $result = $exception->withMetadata(['key' => 'value']);

        expect($result)->toBe($exception);
    });

    test('works with complex metadata', function (): void {
        $exception = TestDomainException::withMessage('Test')
            ->withMetadata([
                'query' => 'SELECT * FROM users',
                'bindings' => [1, 2, 3],
                'execution_time' => 42.5,
            ]);

        expect($exception->getMetadata())->toBe([
            'query' => 'SELECT * FROM users',
            'bindings' => [1, 2, 3],
            'execution_time' => 42.5,
        ]);
    });
});

describe('notes', function (): void {
    test('adds single note', function (): void {
        $exception = TestDomainException::withMessage('Error');

        $exception->addNote('First note');

        expect($exception->getNotes())->toBe(['First note'])
            ->and($exception->hasNotes())->toBeTrue();
    });

    test('adds multiple notes via chaining', function (): void {
        $exception = TestDomainException::withMessage('Error');

        $exception->addNote('First note')
            ->addNote('Second note')
            ->addNote('Third note');

        expect($exception->getNotes())->toBe([
            'First note',
            'Second note',
            'Third note',
        ])
            ->and($exception->hasNotes())->toBeTrue();
    });

    test('adds multiple notes at once', function (): void {
        $exception = TestDomainException::withMessage('Error');

        $exception->addNotes([
            'Processing payment',
            'Using Stripe gateway',
            'Customer ID: 123',
        ]);

        expect($exception->getNotes())->toHaveCount(3)
            ->and($exception->getNotes()[0])->toBe('Processing payment')
            ->and($exception->getNotes()[1])->toBe('Using Stripe gateway')
            ->and($exception->getNotes()[2])->toBe('Customer ID: 123');
    });

    test('combines addNote and addNotes', function (): void {
        $exception = TestDomainException::withMessage('Error');

        $exception->addNote('First note')
            ->addNotes(['Second note', 'Third note'])
            ->addNote('Fourth note');

        expect($exception->getNotes())->toHaveCount(4)
            ->and($exception->getNotes())->toBe([
                'First note',
                'Second note',
                'Third note',
                'Fourth note',
            ]);
    });

    test('returns empty array when no notes', function (): void {
        $exception = TestDomainException::withMessage('Error');

        expect($exception->getNotes())->toBe([])
            ->and($exception->hasNotes())->toBeFalse();
    });

    test('hasNotes returns true when notes exist', function (): void {
        $exception = TestDomainException::withMessage('Error');

        expect($exception->hasNotes())->toBeFalse();

        $exception->addNote('Note');

        expect($exception->hasNotes())->toBeTrue();
    });

    test('formats notes as numbered list', function (): void {
        $exception = TestDomainException::withMessage('Error');

        $exception->addNotes([
            'Processing payment',
            'Using Stripe gateway',
            'Customer retry attempt',
        ]);

        $formatted = $exception->getFormattedNotes();

        expect($formatted)->toContain('Notes:')
            ->and($formatted)->toContain('1. Processing payment')
            ->and($formatted)->toContain('2. Using Stripe gateway')
            ->and($formatted)->toContain('3. Customer retry attempt');
    });

    test('getFormattedNotes returns empty string when no notes', function (): void {
        $exception = TestDomainException::withMessage('Error');

        expect($exception->getFormattedNotes())->toBe('');
    });

    test('preserves note order', function (): void {
        $exception = TestDomainException::withMessage('Error');

        for ($i = 1; $i <= 5; ++$i) {
            $exception->addNote('Note '.$i);
        }

        $notes = $exception->getNotes();

        foreach ($notes as $index => $note) {
            expect($note)->toBe('Note '.($index + 1));
        }
    });
});

describe('combined usage', function (): void {
    test('chains all context methods together', function (): void {
        $exception = TestDomainException::withMessage('Payment failed')
            ->withContext(['user_id' => 123, 'amount' => 99.99])
            ->withTags(['payment', 'critical'])
            ->withMetadata(['gateway' => 'stripe', 'attempt' => 3])
            ->addNote('Processing payment for order #123');

        expect($exception->getContext())->toBe(['user_id' => 123, 'amount' => 99.99])
            ->and($exception->getTags())->toBe(['payment', 'critical'])
            ->and($exception->getMetadata())->toBe(['gateway' => 'stripe', 'attempt' => 3])
            ->and($exception->getNotes())->toBe(['Processing payment for order #123']);
    });

    test('maintains all data through exception throw', function (): void {
        try {
            TestDomainException::withMessage('Test error')
                ->withContext(['user_id' => 456])
                ->withTags(['test'])
                ->withMetadata(['debug' => true])
                ->addNote('Test note')
                ->throwIf(true);
        } catch (TestDomainException $testDomainException) {
            expect($testDomainException->getContext())->toBe(['user_id' => 456])
                ->and($testDomainException->getTags())->toBe(['test'])
                ->and($testDomainException->getMetadata())->toBe(['debug' => true])
                ->and($testDomainException->getNotes())->toBe(['Test note']);

            return;
        }

        throw TestExpectationFailedException::expectedThrow(TestDomainException::class);
    });

    test('empty getters return empty arrays by default', function (): void {
        $exception = TestDomainException::withMessage('Test');

        expect($exception->getContext())->toBe([])
            ->and($exception->getTags())->toBe([])
            ->and($exception->getMetadata())->toBe([])
            ->and($exception->getNotes())->toBe([]);
    });

    test('adds notes during exception propagation', function (): void {
        try {
            try {
                $exception = TestDomainException::withMessage('Database error');
                $exception->addNote('Query: SELECT * FROM users');
                $exception->addNote('Connection: primary');

                throw $exception;
            } catch (TestDomainException $testDomainException) {
                $testDomainException->addNote('Caught in repository layer');
                $testDomainException->addNote('Attempting retry');

                throw $testDomainException;
            }
        } catch (TestDomainException $testDomainException) {
            $testDomainException->addNote('Caught in service layer');

            expect($testDomainException->getNotes())->toHaveCount(5)
                ->and($testDomainException->getNotes())->toBe([
                    'Query: SELECT * FROM users',
                    'Connection: primary',
                    'Caught in repository layer',
                    'Attempting retry',
                    'Caught in service layer',
                ]);

            return;
        }

        throw TestExpectationFailedException::expectedThrow(TestDomainException::class);
    });
});
