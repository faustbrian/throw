<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Throw\Support\HttpStatusCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Cline\Throw\ensure;

describe('HttpStatusCode enum', function (): void {
    test('has correct 1xx informational codes', function (): void {
        expect(HttpStatusCode::Continue->value)->toBe(100)
            ->and(HttpStatusCode::SwitchingProtocols->value)->toBe(101)
            ->and(HttpStatusCode::Processing->value)->toBe(102)
            ->and(HttpStatusCode::EarlyHints->value)->toBe(103);
    });

    test('has correct 2xx success codes', function (): void {
        expect(HttpStatusCode::Ok->value)->toBe(200)
            ->and(HttpStatusCode::Created->value)->toBe(201)
            ->and(HttpStatusCode::Accepted->value)->toBe(202)
            ->and(HttpStatusCode::NoContent->value)->toBe(204);
    });

    test('has correct 3xx redirection codes', function (): void {
        expect(HttpStatusCode::MovedPermanently->value)->toBe(301)
            ->and(HttpStatusCode::Found->value)->toBe(302)
            ->and(HttpStatusCode::SeeOther->value)->toBe(303)
            ->and(HttpStatusCode::NotModified->value)->toBe(304)
            ->and(HttpStatusCode::TemporaryRedirect->value)->toBe(307)
            ->and(HttpStatusCode::PermanentRedirect->value)->toBe(308);
    });

    test('has correct 4xx client error codes', function (): void {
        expect(HttpStatusCode::BadRequest->value)->toBe(400)
            ->and(HttpStatusCode::Unauthorized->value)->toBe(401)
            ->and(HttpStatusCode::PaymentRequired->value)->toBe(402)
            ->and(HttpStatusCode::Forbidden->value)->toBe(403)
            ->and(HttpStatusCode::NotFound->value)->toBe(404)
            ->and(HttpStatusCode::MethodNotAllowed->value)->toBe(405)
            ->and(HttpStatusCode::Conflict->value)->toBe(409)
            ->and(HttpStatusCode::UnprocessableEntity->value)->toBe(422)
            ->and(HttpStatusCode::TooManyRequests->value)->toBe(429);
    });

    test('has correct 5xx server error codes', function (): void {
        expect(HttpStatusCode::InternalServerError->value)->toBe(500)
            ->and(HttpStatusCode::NotImplemented->value)->toBe(501)
            ->and(HttpStatusCode::BadGateway->value)->toBe(502)
            ->and(HttpStatusCode::ServiceUnavailable->value)->toBe(503)
            ->and(HttpStatusCode::GatewayTimeout->value)->toBe(504);
    });

    test('has teapot status code', function (): void {
        expect(HttpStatusCode::ImATeapot->value)->toBe(418);
    });

    test('can be used in match expressions', function (): void {
        $code = HttpStatusCode::NotFound;

        $result = match ($code) {
            HttpStatusCode::Ok => 'success',
            HttpStatusCode::NotFound => 'not found',
            HttpStatusCode::InternalServerError => 'error',
            default => 'unknown',
        };

        expect($result)->toBe('not found');
    });

    test('can be compared', function (): void {
        expect(HttpStatusCode::Ok === HttpStatusCode::Ok)->toBeTrue()
            ->and(HttpStatusCode::Ok === HttpStatusCode::NotFound)->toBeFalse();
    });
});

describe('integration with Assertion', function (): void {
    test('works with orAbort', function (): void {
        expect(fn () => ensure(false)->orAbort(HttpStatusCode::NotFound))
            ->toThrow(HttpException::class);
    });

    test('works with HTTP helper methods', function (): void {
        // These methods internally use HttpStatusCode enum
        expect(fn () => ensure(false)->orBadRequest())->toThrow(HttpException::class);
        expect(fn () => ensure(false)->orUnauthorized())->toThrow(HttpException::class);
        expect(fn () => ensure(false)->orForbidden())->toThrow(HttpException::class);
        expect(fn () => ensure(false)->orNotFound())->toThrow(HttpException::class);
    });
});
