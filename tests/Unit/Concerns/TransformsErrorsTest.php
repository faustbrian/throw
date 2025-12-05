<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Date;
use Tests\Exceptions\TestExpectationFailedException;
use Tests\Fixtures\TestDomainException;

describe('mapMessage', function (): void {
    test('transforms exception message', function (): void {
        $exception = TestDomainException::withMessage('error');

        $exception->mapMessage(fn ($msg): string => mb_strtoupper($msg));

        expect($exception->getMessage())->toBe('ERROR');
    });

    test('adds prefix to message', function (): void {
        $exception = TestDomainException::withMessage('Connection failed');

        $exception->mapMessage(fn ($msg): string => 'Database: '.$msg);

        expect($exception->getMessage())->toBe('Database: Connection failed');
    });

    test('chains multiple transformations', function (): void {
        $exception = TestDomainException::withMessage('error');

        $exception->mapMessage(fn ($msg): string => mb_strtoupper($msg))
            ->mapMessage(fn ($msg): string => '[CRITICAL] '.$msg);

        expect($exception->getMessage())->toBe('[CRITICAL] ERROR');
    });

    test('returns same instance for fluent chaining', function (): void {
        $exception = TestDomainException::withMessage('error');
        $result = $exception->mapMessage(fn ($msg): string => $msg);

        expect($result)->toBe($exception);
    });
});

describe('mapContext', function (): void {
    test('transforms context array', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withContext(['user_id' => 123]);

        $exception->mapContext(fn ($ctx): array => [...$ctx, 'transformed' => true]);

        expect($exception->getContext())->toBe([
            'user_id' => 123,
            'transformed' => true,
        ]);
    });

    test('filters context keys', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withContext(['password' => 'secret', 'username' => 'john']);

        $exception->mapContext(fn ($ctx): array => array_diff_key($ctx, ['password' => 1]));

        expect($exception->getContext())->toBe(['username' => 'john'])
            ->and($exception->getContext())->not->toHaveKey('password');
    });

    test('adds timestamp to context', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withContext(['action' => 'login']);

        $timestamp = now()->toIso8601String();

        $exception->mapContext(fn ($ctx): array => [...$ctx, 'timestamp' => $timestamp]);

        expect($exception->getContext())->toHaveKey('timestamp')
            ->and($exception->getContext()['timestamp'])->toBe($timestamp);
    });

    test('chains context transformations', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withContext(['value' => 10]);

        $exception->mapContext(fn ($ctx): array => [...$ctx, 'doubled' => $ctx['value'] * 2])
            ->mapContext(fn ($ctx): array => [...$ctx, 'tripled' => $ctx['value'] * 3]);

        expect($exception->getContext())->toBe([
            'value' => 10,
            'doubled' => 20,
            'tripled' => 30,
        ]);
    });
});

describe('mapMetadata', function (): void {
    test('transforms metadata array', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withMetadata(['query' => 'SELECT * FROM users']);

        $exception->mapMetadata(fn ($meta): array => [...$meta, 'execution_time' => 42]);

        expect($exception->getMetadata())->toBe([
            'query' => 'SELECT * FROM users',
            'execution_time' => 42,
        ]);
    });

    test('filters large metadata values', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withMetadata(['small' => 'ok', 'large' => str_repeat('x', 2_000)]);

        $exception->mapMetadata(fn ($meta): array => array_filter(
            $meta,
            fn ($v): bool => mb_strlen((string) $v) < 100,
        ));

        expect($exception->getMetadata())->toHaveKey('small')
            ->and($exception->getMetadata())->not->toHaveKey('large');
    });
});

describe('mapTags', function (): void {
    test('transforms tags array', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withTags(['Payment', 'Critical']);

        $exception->mapTags(fn ($tags): array => array_map(strtolower(...), $tags));

        expect($exception->getTags())->toBe(['payment', 'critical']);
    });

    test('adds environment tag', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withTags(['api']);

        $exception->mapTags(fn ($tags): array => [...$tags, 'testing']);

        expect($exception->getTags())->toBe(['api', 'testing']);
    });

    test('deduplicates tags', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withTags(['critical', 'payment', 'critical']);

        $exception->mapTags(fn ($tags): array => array_values(array_unique($tags)));

        expect($exception->getTags())->toBe(['critical', 'payment']);
    });
});

describe('mapNotes', function (): void {
    test('transforms notes array', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->addNotes(['First note', 'Second note']);

        $exception->mapNotes(fn ($notes): array => array_map(strtoupper(...), $notes));

        expect($exception->getNotes())->toBe(['FIRST NOTE', 'SECOND NOTE']);
    });

    test('adds timestamps to notes', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->addNotes(['Note 1', 'Note 2']);

        $timestamp = '2024-01-01 12:00:00';

        $exception->mapNotes(fn ($notes): array => array_map(
            fn (string $n): string => sprintf('[%s] %s', $timestamp, $n),
            $notes,
        ));

        expect($exception->getNotes()[0])->toBe('[2024-01-01 12:00:00] Note 1')
            ->and($exception->getNotes()[1])->toBe('[2024-01-01 12:00:00] Note 2');
    });

    test('filters short notes', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->addNotes(['Short', 'This is a longer note', 'OK']);

        $exception->mapNotes(fn ($notes): array => array_values(
            array_filter($notes, fn (string $n): bool => mb_strlen($n) > 5),
        ));

        expect($exception->getNotes())->toHaveCount(1)
            ->and($exception->getNotes()[0])->toBe('This is a longer note');
    });
});

describe('transform', function (): void {
    test('applies comprehensive transformation', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withContext(['user_id' => 123])
            ->withTags(['api']);

        $exception->transform(function ($e): void {
            $e->mapMessage(fn ($msg): string => 'Critical: '.$msg)
                ->mapContext(fn ($ctx): array => [...$ctx, 'severity' => 'high'])
                ->addNote('Exception transformed');
        });

        expect($exception->getMessage())->toBe('Critical: error')
            ->and($exception->getContext())->toHaveKey('severity')
            ->and($exception->getNotes())->toContain('Exception transformed');
    });

    test('conditional transformation', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withContext(['debug' => true, 'user_id' => 123]);

        $isProd = false;

        $exception->transform(function ($e) use ($isProd): void {
            if ($isProd) {
                $e->mapContext(fn ($ctx): array => array_diff_key($ctx, ['debug' => 1]));
            } else {
                $e->addNote('Running in development mode');
            }
        });

        expect($exception->getContext())->toHaveKey('debug')
            ->and($exception->getNotes())->toContain('Running in development mode');
    });

    test('returns same instance', function (): void {
        $exception = TestDomainException::withMessage('error');
        $result = $exception->transform(fn ($e): mixed => null);

        expect($result)->toBe($exception);
    });
});

describe('combined transformations', function (): void {
    test('chains all transformation methods', function (): void {
        $exception = TestDomainException::withMessage('payment failed')
            ->withContext(['amount' => 100])
            ->withTags(['Payment'])
            ->withMetadata(['gateway' => 'stripe'])
            ->addNote('Initial note');

        $exception->mapMessage(fn ($msg): string => ucfirst($msg))
            ->mapContext(fn ($ctx): array => [...$ctx, 'currency' => 'USD'])
            ->mapTags(fn ($tags): array => array_map(strtolower(...), $tags))
            ->mapMetadata(fn ($meta): array => [...$meta, 'timestamp' => Date::now()->getTimestamp()])
            ->mapNotes(fn ($notes): array => [...$notes, 'Transformed']);

        expect($exception->getMessage())->toBe('Payment failed')
            ->and($exception->getContext())->toHaveKey('currency')
            ->and($exception->getTags())->toBe(['payment'])
            ->and($exception->getMetadata())->toHaveKey('timestamp')
            ->and($exception->getNotes())->toHaveCount(2);
    });

    test('preserves transformations through throw', function (): void {
        try {
            TestDomainException::withMessage('error')
                ->withContext(['id' => 1])
                ->mapMessage(fn ($msg): string => 'Transformed: '.$msg)
                ->mapContext(fn ($ctx): array => [...$ctx, 'transformed' => true])
                ->throwIf(true);
        } catch (TestDomainException $testDomainException) {
            expect($testDomainException->getMessage())->toBe('Transformed: error')
                ->and($testDomainException->getContext())->toHaveKey('transformed');

            return;
        }

        throw TestExpectationFailedException::expectedThrow(TestDomainException::class);
    });
});

describe('real-world usage', function (): void {
    test('normalizes error messages', function (): void {
        $exception = TestDomainException::withMessage('connection  failed  ');

        $exception->mapMessage(fn ($msg): string => mb_trim(preg_replace('/\s+/', ' ', $msg)));

        expect($exception->getMessage())->toBe('connection failed');
    });

    test('adds contextual prefix based on layer', function (): void {
        $exception = TestDomainException::withMessage('Query failed');

        $exception->mapMessage(fn ($msg): string => '[Repository Layer] '.$msg);

        expect($exception->getMessage())->toBe('[Repository Layer] Query failed');
    });

    test('sanitizes sensitive context data', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withContext([
                'email' => 'user@example.com',
                'password' => 'secret123',
                'api_key' => 'key_123',
            ]);

        $sensitive = ['password', 'api_key'];

        $exception->mapContext(fn ($ctx): array => array_diff_key(
            $ctx,
            array_flip($sensitive),
        ));

        expect($exception->getContext())->toHaveKey('email')
            ->and($exception->getContext())->not->toHaveKey('password')
            ->and($exception->getContext())->not->toHaveKey('api_key');
    });

    test('enriches metadata with environment info', function (): void {
        $exception = TestDomainException::withMessage('error')
            ->withMetadata(['error_code' => 500]);

        $exception->mapMetadata(fn ($meta): array => [
            ...$meta,
            'php_version' => \PHP_VERSION,
            'memory_usage' => memory_get_usage(true),
        ]);

        expect($exception->getMetadata())->toHaveKey('php_version')
            ->and($exception->getMetadata())->toHaveKey('memory_usage');
    });
});
