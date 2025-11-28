<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Throw\Support\DeferredCleanup;

use function Cline\Throw\errdefer;

describe('DeferredCleanup', function (): void {
    test('cleanup does not run when no error occurs', function (): void {
        $cleaned = false;

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$cleaned): void {
            $cleaned = true;
        });

        expect($cleaned)->toBeFalse();

        unset($deferred);

        expect($cleaned)->toBeTrue();
    });

    test('cleanup runs on manual trigger', function (): void {
        $cleaned = false;

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$cleaned): void {
            $cleaned = true;
        });

        $deferred->cleanup();

        expect($cleaned)->toBeTrue();
    });

    test('cleanup runs only once', function (): void {
        $count = 0;

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$count): void {
            ++$count;
        });

        $deferred->cleanup();
        $deferred->cleanup();
        $deferred->cleanup();

        expect($count)->toBe(1);
    });

    test('multiple cleanup callbacks execute in reverse order', function (): void {
        $order = [];

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$order): void {
            $order[] = 'first';
        });
        $deferred->onError(function () use (&$order): void {
            $order[] = 'second';
        });
        $deferred->onError(function () use (&$order): void {
            $order[] = 'third';
        });

        $deferred->cleanup();

        expect($order)->toBe(['third', 'second', 'first']);
    });

    test('run executes callback and returns value', function (): void {
        $deferred = new DeferredCleanup();

        $result = $deferred->run(fn (): string => 'success');

        expect($result)->toBe('success');
    });

    test('run triggers cleanup on exception', function (): void {
        $cleaned = false;

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$cleaned): void {
            $cleaned = true;
        });

        try {
            $deferred->run(function (): void {
                throw new RuntimeException('Error');
            });
        } catch (RuntimeException $runtimeException) {
            expect($cleaned)->toBeTrue()
                ->and($runtimeException->getMessage())->toBe('Error');

            return;
        }

        throw new Exception('Expected exception to be thrown');
    });

    test('run re-throws exception after cleanup', function (): void {
        $deferred = new DeferredCleanup();
        $deferred->onError(fn (): bool => true);

        expect(fn (): mixed => $deferred->run(function (): void {
            throw new RuntimeException('Test error');
        }))->toThrow(RuntimeException::class, 'Test error');
    });

    test('cleanup does not run on successful operation', function (): void {
        $cleaned = false;

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$cleaned): void {
            $cleaned = true;
        });

        $result = $deferred->run(fn (): string => 'success');

        expect($result)->toBe('success')
            ->and($cleaned)->toBeFalse();
    });

    test('fluent interface for registering callbacks', function (): void {
        $order = [];

        $deferred = new DeferredCleanup();

        $result = $deferred->onError(function () use (&$order): void {
            $order[] = 'first';
        })
            ->onError(function () use (&$order): void {
                $order[] = 'second';
            })
            ->onError(function () use (&$order): void {
                $order[] = 'third';
            });

        expect($result)->toBe($deferred);

        $deferred->cleanup();

        expect($order)->toBe(['third', 'second', 'first']);
    });
});

describe('errdefer helper', function (): void {
    test('creates DeferredCleanup instance', function (): void {
        $deferred = errdefer();

        expect($deferred)->toBeInstanceOf(DeferredCleanup::class);
    });

    test('works with cleanup callbacks', function (): void {
        $cleaned = false;

        $deferred = errdefer();
        $deferred->onError(function () use (&$cleaned): void {
            $cleaned = true;
        });

        $deferred->cleanup();

        expect($cleaned)->toBeTrue();
    });

    test('works with run method', function (): void {
        $cleaned = false;

        $deferred = errdefer();
        $deferred->onError(function () use (&$cleaned): void {
            $cleaned = true;
        });

        try {
            $deferred->run(function (): void {
                throw new RuntimeException('Error');
            });
        } catch (RuntimeException) {
            expect($cleaned)->toBeTrue();

            return;
        }

        throw new Exception('Expected exception');
    });
});

describe('real-world usage', function (): void {
    test('database transaction cleanup', function (): void {
        $transactions = [];

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$transactions): void {
            $transactions[] = 'rollback';
        });

        $transactions[] = 'begin';

        try {
            $deferred->run(function (): void {
                throw new RuntimeException('Transaction failed');
            });
        } catch (RuntimeException) {
            expect($transactions)->toBe(['begin', 'rollback']);

            return;
        }

        throw new Exception('Expected exception');
    });

    test('file cleanup on error', function (): void {
        $files = ['temp.txt' => 'exists'];

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$files): void {
            unset($files['temp.txt']);
        });

        try {
            $deferred->run(function (): void {
                throw new RuntimeException('Processing failed');
            });
        } catch (RuntimeException) {
            expect($files)->not->toHaveKey('temp.txt');

            return;
        }

        throw new Exception('Expected exception');
    });

    test('multiple resource cleanup', function (): void {
        $resources = ['file' => 'open', 'connection' => 'open', 'lock' => 'acquired'];

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$resources): void {
            $resources['file'] = 'closed';
        });
        $deferred->onError(function () use (&$resources): void {
            $resources['connection'] = 'closed';
        });
        $deferred->onError(function () use (&$resources): void {
            $resources['lock'] = 'released';
        });

        try {
            $deferred->run(function (): void {
                throw new RuntimeException('Operation failed');
            });
        } catch (RuntimeException) {
            expect($resources)->toBe([
                'file' => 'closed',
                'connection' => 'closed',
                'lock' => 'released',
            ]);

            return;
        }

        throw new Exception('Expected exception');
    });

    test('successful operation does not trigger cleanup', function (): void {
        $resources = ['file' => 'open'];

        $deferred = new DeferredCleanup();
        $deferred->onError(function () use (&$resources): void {
            $resources['file'] = 'closed';
        });

        $result = $deferred->run(fn (): string => 'success');

        expect($result)->toBe('success')
            ->and($resources['file'])->toBe('open');
    });

    test('nested cleanup handlers', function (): void {
        $log = [];

        $outer = new DeferredCleanup();
        $outer->onError(function () use (&$log): void {
            $log[] = 'outer cleanup';
        });

        try {
            $outer->run(function () use (&$log): void {
                $log[] = 'outer start';

                $inner = new DeferredCleanup();
                $inner->onError(function () use (&$log): void {
                    $log[] = 'inner cleanup';
                });

                try {
                    $inner->run(function () use (&$log): void {
                        $log[] = 'inner start';

                        throw new RuntimeException('Inner error');
                    });
                } catch (RuntimeException) {
                    $log[] = 'inner caught';

                    throw new RuntimeException('Outer error');
                }
            });
        } catch (RuntimeException) {
            expect($log)->toBe([
                'outer start',
                'inner start',
                'inner cleanup',
                'inner caught',
                'outer cleanup',
            ]);

            return;
        }

        throw new Exception('Expected exception');
    });
});
