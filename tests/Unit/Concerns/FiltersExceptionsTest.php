<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Tests\Fixtures\TestDomainException;

describe('findFirst', function (): void {
    test('finds first matching exception in chain', function (): void {
        $root = new PDOException('Root');
        $middle = new OverflowException('Middle', 0, $root);
        $top = TestDomainException::withMessage('Top')->wrap($middle);

        expect($top->findFirst(OverflowException::class))->toBe($middle)
            ->and($top->findFirst(PDOException::class))->toBe($root);
    });

    test('returns self if it matches', function (): void {
        $exception = TestDomainException::withMessage('error');

        expect($exception->findFirst(TestDomainException::class))->toBe($exception);
    });

    test('returns null when no match found', function (): void {
        $exception = TestDomainException::withMessage('error');

        expect($exception->findFirst(PDOException::class))->toBeNull();
    });

    test('searches through deep chains', function (): void {
        $level3 = new PDOException('Level 3');
        $level2 = new InvalidArgumentException('Level 2', 0, $level3);
        $level1 = new LogicException('Level 1', 0, $level2);
        $top = TestDomainException::withMessage('Top')->wrap($level1);

        expect($top->findFirst(PDOException::class))->toBe($level3);
    });
});

describe('findAll', function (): void {
    test('finds all matching exceptions in chain', function (): void {
        $logic1 = new LogicException('First');
        $logic2 = new LogicException('Second', 0, $logic1);
        $top = TestDomainException::withMessage('Top')->wrap($logic2);

        $results = $top->findAll(LogicException::class);

        expect($results)->toHaveCount(2)
            ->and($results[0])->toBe($logic2)
            ->and($results[1])->toBe($logic1);
    });

    test('includes self if it matches', function (): void {
        $domain1 = TestDomainException::withMessage('First');
        $domain2 = TestDomainException::withMessage('Second')->wrap($domain1);

        $results = $domain2->findAll(TestDomainException::class);

        expect($results)->toHaveCount(2)
            ->and($results[0])->toBe($domain2)
            ->and($results[1])->toBe($domain1);
    });

    test('returns empty array when no matches found', function (): void {
        $exception = TestDomainException::withMessage('error');

        expect($exception->findAll(PDOException::class))->toBeEmpty();
    });

    test('finds mixed types in chain', function (): void {
        $pdo = new PDOException('PDO');
        $runtime = new RuntimeException('Runtime', 0, $pdo);
        $invalid = new InvalidArgumentException('Invalid', 0, $runtime);
        $top = TestDomainException::withMessage('Top')->wrap($invalid);

        expect($top->findAll(PDOException::class))->toHaveCount(1)
            ->and($top->findAll(InvalidArgumentException::class))->toHaveCount(1)
            ->and($top->findAll(TestDomainException::class))->toHaveCount(1);
    });
});

describe('getChain', function (): void {
    test('returns single exception when no previous', function (): void {
        $exception = TestDomainException::withMessage('error');

        $chain = $exception->getChain();

        expect($chain)->toHaveCount(1)
            ->and($chain[0])->toBe($exception);
    });

    test('returns full chain in order', function (): void {
        $level3 = new RuntimeException('Level 3');
        $level2 = new InvalidArgumentException('Level 2', 0, $level3);
        $level1 = new LogicException('Level 1', 0, $level2);
        $top = TestDomainException::withMessage('Top')->wrap($level1);

        $chain = $top->getChain();

        expect($chain)->toHaveCount(4)
            ->and($chain[0])->toBe($top)
            ->and($chain[1])->toBe($level1)
            ->and($chain[2])->toBe($level2)
            ->and($chain[3])->toBe($level3);
    });

    test('works with long chains', function (): void {
        $exception = TestDomainException::withMessage('Start');

        for ($i = 0; $i < 10; ++$i) {
            $prev = $exception;
            $exception = TestDomainException::withMessage('Level '.$i)->wrap($prev);
        }

        expect($exception->getChain())->toHaveCount(11);
    });
});

describe('filterChain', function (): void {
    test('filters by message content', function (): void {
        $error1 = new RuntimeException('Connection failed');
        $error2 = new RuntimeException('Validation error', 0, $error1);
        $error3 = new RuntimeException('Connection timeout', 0, $error2);
        $top = TestDomainException::withMessage('Top')->wrap($error3);

        $connectionErrors = $top->filterChain(
            fn ($e): bool => str_contains($e->getMessage(), 'Connection'),
        );

        expect($connectionErrors)->toHaveCount(2);
    });

    test('filters by exception code', function (): void {
        $error1 = new RuntimeException('Error 1', 500);
        $error2 = new RuntimeException('Error 2', 200, $error1);
        $error3 = new RuntimeException('Error 3', 500, $error2);
        $top = TestDomainException::withMessage('Top')->wrap($error3);

        $serverErrors = $top->filterChain(
            fn ($e): bool => $e->getCode() === 500,
        );

        expect($serverErrors)->toHaveCount(2);
    });

    test('filters by class namespace', function (): void {
        $pdo = new PDOException('Database error');
        $runtime = new RuntimeException('Runtime error', 0, $pdo);
        $top = TestDomainException::withMessage('Top')->wrap($runtime);

        $pdoErrors = $top->filterChain(
            fn ($e): bool => $e instanceof PDOException,
        );

        expect($pdoErrors)->toHaveCount(1)
            ->and($pdoErrors[0])->toBe($pdo);
    });

    test('returns empty array when no matches', function (): void {
        $exception = TestDomainException::withMessage('error');

        $results = $exception->filterChain(
            fn ($e): bool => $e->getCode() > 0,
        );

        expect($results)->toBeEmpty();
    });

    test('complex filter conditions', function (): void {
        $error1 = new RuntimeException('Critical error', 500);
        $error2 = new RuntimeException('Warning', 300, $error1);
        $error3 = new RuntimeException('Critical failure', 500, $error2);
        $top = TestDomainException::withMessage('Info')->wrap($error3);

        $criticalServerErrors = $top->filterChain(fn ($e): bool => $e->getCode() === 500
            && str_contains($e->getMessage(), 'Critical'));

        expect($criticalServerErrors)->toHaveCount(2);
    });
});

describe('hasInChain', function (): void {
    test('returns true when type exists in chain', function (): void {
        $pdo = new PDOException('Database error');
        $runtime = new RuntimeException('Runtime error', 0, $pdo);
        $top = TestDomainException::withMessage('Top')->wrap($runtime);

        expect($top->hasInChain(PDOException::class))->toBeTrue()
            ->and($top->hasInChain(RuntimeException::class))->toBeTrue()
            ->and($top->hasInChain(TestDomainException::class))->toBeTrue();
    });

    test('returns false when type not in chain', function (): void {
        $exception = TestDomainException::withMessage('error');

        expect($exception->hasInChain(PDOException::class))->toBeFalse();
    });

    test('checks self', function (): void {
        $exception = TestDomainException::withMessage('error');

        expect($exception->hasInChain(TestDomainException::class))->toBeTrue();
    });
});

describe('getRootCause', function (): void {
    test('returns self when no previous', function (): void {
        $exception = TestDomainException::withMessage('error');

        expect($exception->getRootCause())->toBe($exception);
    });

    test('returns last exception in chain', function (): void {
        $root = new RuntimeException('Root cause');
        $middle = new InvalidArgumentException('Middle', 0, $root);
        $top = TestDomainException::withMessage('Top')->wrap($middle);

        expect($top->getRootCause())->toBe($root);
    });

    test('works with deep chains', function (): void {
        $root = new RuntimeException('Root');
        $exception = TestDomainException::withMessage('Level 0')->wrap($root);

        for ($i = 1; $i < 5; ++$i) {
            $prev = $exception;
            $exception = TestDomainException::withMessage('Level '.$i)->wrap($prev);
        }

        expect($exception->getRootCause())->toBe($root);
    });
});

describe('getChainDepth', function (): void {
    test('returns 1 for single exception', function (): void {
        $exception = TestDomainException::withMessage('error');

        expect($exception->getChainDepth())->toBe(1);
    });

    test('counts full chain', function (): void {
        $level3 = new RuntimeException('Level 3');
        $level2 = new InvalidArgumentException('Level 2', 0, $level3);
        $level1 = new LogicException('Level 1', 0, $level2);
        $top = TestDomainException::withMessage('Top')->wrap($level1);

        expect($top->getChainDepth())->toBe(4);
    });

    test('works with long chains', function (): void {
        $exception = TestDomainException::withMessage('Start');

        for ($i = 0; $i < 10; ++$i) {
            $prev = $exception;
            $exception = TestDomainException::withMessage('Level '.$i)->wrap($prev);
        }

        expect($exception->getChainDepth())->toBe(11);
    });
});

describe('real-world usage', function (): void {
    test('finds validation error in service layer', function (): void {
        $validation = new InvalidArgumentException('Invalid email format');
        $service = new RuntimeException('Service error', 0, $validation);
        $controller = TestDomainException::withMessage('Request failed')->wrap($service);

        $validationError = $controller->findFirst(InvalidArgumentException::class);

        expect($validationError)->not->toBeNull()
            ->and($validationError->getMessage())->toBe('Invalid email format');
    });

    test('logs entire exception chain', function (): void {
        $db = new PDOException('Connection failed');
        $repo = new RuntimeException('Repository error', 0, $db);
        $service = TestDomainException::withMessage('Service error')->wrap($repo);

        $chain = $service->getChain();
        $messages = array_map(fn (Throwable $e): string => $e->getMessage(), $chain);

        expect($messages)->toBe([
            'Service error',
            'Repository error',
            'Connection failed',
        ]);
    });

    test('filters critical errors for alerting', function (): void {
        $warning = new RuntimeException('Warning', 300);
        $critical1 = new RuntimeException('Critical error 1', 500, $warning);
        $info = new RuntimeException('Info', 200, $critical1);
        $critical2 = new RuntimeException('Critical error 2', 500, $info);
        $top = TestDomainException::withMessage('Top')->wrap($critical2);

        $criticalErrors = $top->filterChain(
            fn ($e): bool => $e->getCode() >= 500,
        );

        expect($criticalErrors)->toHaveCount(2);
    });

    test('finds all database errors in chain', function (): void {
        $pdo1 = new PDOException('Query failed');
        $runtime = new RuntimeException('Processing error', 0, $pdo1);
        $pdo2 = new PDOException('Connection lost', 0, $runtime);
        $top = TestDomainException::withMessage('Operation failed')->wrap($pdo2);

        $dbErrors = $top->findAll(PDOException::class);

        expect($dbErrors)->toHaveCount(2)
            ->and($dbErrors[0]->getMessage())->toBe('Connection lost')
            ->and($dbErrors[1]->getMessage())->toBe('Query failed');
    });

    test('detects deep exception wrapping', function (): void {
        $exception = TestDomainException::withMessage('Start');

        for ($i = 0; $i < 10; ++$i) {
            $prev = $exception;
            $exception = TestDomainException::withMessage('Wrapper '.$i)->wrap($prev);
        }

        if ($exception->getChainDepth() > 5) {
            // Would log warning about deep wrapping
            expect($exception->getChainDepth())->toBeGreaterThan(5);
        }
    });
});
