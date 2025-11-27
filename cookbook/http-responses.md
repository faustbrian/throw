# HTTP Responses

The `abortIf()` and `abortUnless()` methods terminate request processing and return HTTP error responses instead of throwing exceptions.

## abortIf()

Aborts the request with an HTTP status code when the condition is `true`:

```php
use App\Exceptions\UnauthorizedException;

// 401 Unauthorized
UnauthorizedException::invalidCredentials()->abortIf(!$user, 401);

// 404 Not Found
NotFoundException::resourceMissing()->abortIf($resource === null, 404);

// 403 Forbidden
ForbiddenException::insufficientPermissions()->abortIf(!$user->isAdmin(), 403);

// Default 500 Internal Server Error
ServerException::unexpectedError()->abortIf($critical->failed());
```

## abortUnless()

Aborts the request when the condition is `false`:

```php
// Require authentication
UnauthorizedException::notAuthenticated()->abortUnless(auth()->check(), 401);

// Require specific permission
ForbiddenException::missingPermission()->abortUnless($user->can('publish'), 403);

// Require resource existence
NotFoundException::resourceNotFound()->abortUnless($post !== null, 404);
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
ValidationException::invalidInput()->abortIf($invalid, 400);
```

### 401 Unauthorized

```php
AuthenticationException::notAuthenticated()->abortUnless(auth()->check(), 401);
```

### 403 Forbidden

```php
AuthorizationException::forbidden()->abortUnless($user->owns($resource), 403);
```

### 404 Not Found

```php
ModelNotFoundException::notFound()->abortIf($model === null, 404);
```

### 409 Conflict

```php
ConflictException::duplicateEntry()->abortIf($exists, 409);
```

### 422 Unprocessable Entity

```php
ValidationException::failed()->abortIf($validator->fails(), 422);
```

### 429 Too Many Requests

```php
RateLimitException::tooManyAttempts()->abortIf($exceeded, 429);
```

### 500 Internal Server Error

```php
ServerException::internalError()->abortIf($failed, 500);
```

### 503 Service Unavailable

```php
ServiceException::unavailable()->abortIf($down, 503);
```

## Controller Example

```php
class PostController extends Controller
{
    public function update(Request $request, int $id): JsonResponse
    {
        $post = Post::find($id);

        // Return 404 if post doesn't exist
        NotFoundException::resourceNotFound()->abortIf($post === null, 404);

        // Return 403 if user doesn't own the post
        ForbiddenException::notOwner()->abortUnless($request->user()->owns($post), 403);

        $post->update($request->validated());

        return response()->json($post);
    }
}
```

## API Middleware Example

```php
class RequireApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        // Return 401 if API key is missing
        UnauthorizedException::missingApiKey()->abortIf(empty($apiKey), 401);

        // Return 403 if API key is invalid
        ForbiddenException::invalidApiKey()->abortUnless($this->isValid($apiKey), 403);

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
UnauthorizedException::notAuthenticated()->abortIf(!$user, 401);
ForbiddenException::missingPermission()->abortUnless($user->can('publish'), 403);
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
    expect(fn () => NotFoundException::resourceNotFound()->abortIf(true, 404))
        ->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});

test('does not abort when condition is false', function () {
    NotFoundException::resourceNotFound()->abortIf(false, 404);

    expect(true)->toBeTrue(); // No exception thrown
});
```
