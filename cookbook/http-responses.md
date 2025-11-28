# HTTP Responses

The package provides multiple ways to abort HTTP requests with status codes: trait methods (`abortIf`, `abortUnless`), assertion helpers (`orAbort`, `orNotFound`, etc.), and a type-safe `HttpStatusCode` enum.

## HttpStatusCode Enum

The `HttpStatusCode` enum provides type-safe HTTP status codes with IDE autocomplete support:

```php
use Cline\Throw\Support\HttpStatusCode;

use function Cline\Throw\ensure;

// Using enum values
ensure($user !== null)->orAbort(HttpStatusCode::NotFound);
ensure($user->isAdmin())->orAbort(HttpStatusCode::Forbidden);
ensure($rateLimiter->allow())->orAbort(HttpStatusCode::TooManyRequests);
```

### Available Status Codes

**1xx Informational:**
- `CONTINUE` (100)
- `SWITCHING_PROTOCOLS` (101)
- `PROCESSING` (102)
- `EARLY_HINTS` (103)

**2xx Success:**
- `OK` (200)
- `CREATED` (201)
- `ACCEPTED` (202)
- `NO_CONTENT` (204)
- `PARTIAL_CONTENT` (206)

**3xx Redirection:**
- `MOVED_PERMANENTLY` (301)
- `FOUND` (302)
- `SEE_OTHER` (303)
- `NOT_MODIFIED` (304)
- `TEMPORARY_REDIRECT` (307)
- `PERMANENT_REDIRECT` (308)

**4xx Client Error:**
- `BAD_REQUEST` (400)
- `UNAUTHORIZED` (401)
- `PAYMENT_REQUIRED` (402)
- `FORBIDDEN` (403)
- `NOT_FOUND` (404)
- `METHOD_NOT_ALLOWED` (405)
- `CONFLICT` (409)
- `PAYLOAD_TOO_LARGE` (413)
- `IM_A_TEAPOT` (418)
- `UNPROCESSABLE_ENTITY` (422)
- `TOO_MANY_REQUESTS` (429)

**5xx Server Error:**
- `INTERNAL_SERVER_ERROR` (500)
- `NOT_IMPLEMENTED` (501)
- `BAD_GATEWAY` (502)
- `SERVICE_UNAVAILABLE` (503)
- `GATEWAY_TIMEOUT` (504)

## Assertion HTTP Helpers

Convenient shorthand methods for common HTTP status codes:

```php
use function Cline\Throw\ensure;

// 400 Bad Request
ensure($input->isValid())->orBadRequest('Invalid input');

// 401 Unauthorized
ensure($token !== null)->orUnauthorized('Authentication required');

// 403 Forbidden
ensure($user->can('admin'))->orForbidden('Access denied');

// 404 Not Found
ensure($post !== null)->orNotFound('Post not found');

// 409 Conflict
ensure(!$user->exists())->orConflict('User already exists');

// 422 Unprocessable Entity
ensure($validation->passes())->orUnprocessable('Validation failed');

// 429 Too Many Requests
ensure($rateLimiter->allow())->orTooManyRequests();

// 500 Internal Server Error
ensure($service->isHealthy())->orServerError();

// 503 Service Unavailable
ensure(!$maintenance->isEnabled())->orServiceUnavailable();
```

## Exception Trait Methods

Both `abortIf()` and `abortUnless()` support boolean values and callbacks for lazy evaluation.

### abortIf()

Aborts the request with an HTTP status code when the condition is `true`:

```php
use App\Exceptions\UnauthorizedException;

// Boolean conditions
UnauthorizedException::invalidCredentials()->abortIf(!$user, HttpStatusCode::Unauthorized);
NotFoundException::resourceMissing()->abortIf($resource === null, HttpStatusCode::NotFound);
ForbiddenException::insufficientPermissions()->abortIf(!$user->isAdmin(), HttpStatusCode::Forbidden);

// Lazy evaluation with callbacks
RateLimitException::exceeded()->abortIf(fn() => !$limiter->allow($key), HttpStatusCode::TooManyRequests);
MaintenanceException::underMaintenance()->abortIf(fn() => $this->isDown(), HttpStatusCode::ServiceUnavailable);

// Default 500 Internal Server Error
ServerException::unexpectedError()->abortIf($critical->failed());
```

### abortUnless()

Aborts the request when the condition is `false`:

```php
use Cline\Throw\Support\HttpStatusCode;

// Boolean conditions
UnauthorizedException::notAuthenticated()->abortUnless(auth()->check(), HttpStatusCode::Unauthorized);
ForbiddenException::missingPermission()->abortUnless($user->can('publish'), HttpStatusCode::Forbidden);
NotFoundException::resourceNotFound()->abortUnless($post !== null, HttpStatusCode::NotFound);

// Lazy evaluation with callbacks
AuthenticationException::required()->abortUnless(fn() => $auth->check(), HttpStatusCode::Unauthorized);
ResourceException::notFound()->abortUnless(fn() => $this->exists($id), HttpStatusCode::NotFound);
```

## Default Status Code

If you don't specify a status code, it defaults to `500`:

```php
// Returns 500 Internal Server Error
ServerException::criticalFailure()->abortIf($condition);
```

## Common HTTP Status Codes

### 400 Bad Request

```php
ValidationException::invalidInput()->abortIf($invalid, HttpStatusCode::BadRequest);
```

### 401 Unauthorized

```php
AuthenticationException::notAuthenticated()->abortUnless(auth()->check(), HttpStatusCode::Unauthorized);
```

### 403 Forbidden

```php
AuthorizationException::forbidden()->abortUnless($user->owns($resource), HttpStatusCode::Forbidden);
```

### 404 Not Found

```php
ModelNotFoundException::notFound()->abortIf($model === null, HttpStatusCode::NotFound);
```

### 409 Conflict

```php
ConflictException::duplicateEntry()->abortIf($exists, HttpStatusCode::Conflict);
```

### 422 Unprocessable Entity

```php
ValidationException::failed()->abortIf($validator->fails(), HttpStatusCode::UnprocessableEntity);
```

### 429 Too Many Requests

```php
RateLimitException::tooManyAttempts()->abortIf($exceeded, HttpStatusCode::TooManyRequests);
```

### 500 Internal Server Error

```php
ServerException::internalError()->abortIf($failed, HttpStatusCode::InternalServerError);
```

### 503 Service Unavailable

```php
ServiceException::unavailable()->abortIf($down, HttpStatusCode::ServiceUnavailable);
```

## Controller Examples

### Using Assertion Helpers

```php
use function Cline\Throw\ensure;

class PostController extends Controller
{
    public function show(Post $post): JsonResponse
    {
        ensure($post->isPublished() || $post->user_id === auth()->id())
            ->orForbidden();

        return response()->json(new PostResource($post));
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        ensure($post->user_id === auth()->id())
            ->orForbidden('You cannot edit this post');

        ensure(!$post->isLocked())
            ->orConflict('Post is locked');

        $post->update($request->validated());

        return response()->json(new PostResource($post));
    }
}
```

### Using Exception Trait Methods

```php
use Cline\Throw\Support\HttpStatusCode;

class PostController extends Controller
{
    public function update(Request $request, int $id): JsonResponse
    {
        $post = Post::find($id);

        // Return 404 if post doesn't exist
        NotFoundException::resourceNotFound()->abortIf($post === null, HttpStatusCode::NotFound);

        // Return 403 if user doesn't own the post
        ForbiddenException::notOwner()->abortUnless($request->user()->owns($post), HttpStatusCode::Forbidden);

        $post->update($request->validated());

        return response()->json($post);
    }
}
```

## Middleware Examples

### Using Assertion Helpers

```php
use function Cline\Throw\ensure;

class EnsureUserIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        ensure($request->user()?->hasVerifiedEmail())
            ->orForbidden('Email must be verified');

        return $next($request);
    }
}

class EnsureApiRateLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $limiter = app(RateLimiter::class);

        ensure($limiter->attempt($request->user()->id, 60, fn() => true))
            ->orTooManyRequests('Rate limit exceeded');

        return $next($request);
    }
}
```

### Using Exception Trait Methods

```php
use Cline\Throw\Support\HttpStatusCode;

class RequireApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        // Return 401 if API key is missing
        UnauthorizedException::missingApiKey()->abortIf(empty($apiKey), HttpStatusCode::Unauthorized);

        // Return 403 if API key is invalid
        ForbiddenException::invalidApiKey()->abortUnless($this->isValid($apiKey), HttpStatusCode::Forbidden);

        return $next($request);
    }
}
```

## Comparison with abort() Helper

### Before (using abort helper)

```php
if (!$user) {
    abort(401, 'Unauthorized');
}

if (!$user->can('publish')) {
    abort(403, 'Forbidden');
}
```

### After (using Throw trait)

```php
UnauthorizedException::notAuthenticated()->abortIf(!$user, HttpStatusCode::Unauthorized);
ForbiddenException::missingPermission()->abortUnless($user->can('publish'), HttpStatusCode::Forbidden);
```

The fluent version provides:
- More descriptive exception classes
- Better code organization
- Consistent exception handling patterns
- Easier testing and mocking

## Custom Response Headers

While `abortIf()` and `abortUnless()` use Laravel's `abort()` helper internally, you can extend this pattern by catching the exception in a handler:

```php
class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof RateLimitException) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 429)
            ->header('Retry-After', 60);
        }

        return parent::render($request, $e);
    }
}
```

## Testing

Test HTTP aborts using Laravel's exception assertions:

```php
test('aborts with 404 when post not found', function () {
    expect(fn () => NotFoundException::resourceNotFound()->abortIf(true, HttpStatusCode::NotFound))
        ->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});

test('does not abort when condition is false', function () {
    NotFoundException::resourceNotFound()->abortIf(false, HttpStatusCode::NotFound);

    expect(true)->toBeTrue(); // No exception thrown
});
```
