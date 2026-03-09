## Table of Contents

1. [Getting Started](#doc-docs-readme) (`docs/README.md`)
2. [Basic Usage](#doc-docs-basic-usage) (`docs/basic-usage.md`)
3. [HTTP Responses](#doc-docs-http-responses) (`docs/http-responses.md`)
4. [Integration Patterns](#doc-docs-integration-patterns) (`docs/integration-patterns.md`)
5. [Assertions](#doc-docs-assertions) (`docs/assertions.md`)
6. [Attempt Monad](#doc-docs-attempt-monad) (`docs/attempt-monad.md`)
7. [Base Exceptions](#doc-docs-base-exceptions) (`docs/base-exceptions.md`)
8. [Deferred Cleanup](#doc-docs-deferred-cleanup) (`docs/deferred-cleanup.md`)
9. [Error Comparison](#doc-docs-error-comparison) (`docs/error-comparison.md`)
10. [Error Context](#doc-docs-error-context) (`docs/error-context.md`)
11. [Error Wrapping](#doc-docs-error-wrapping) (`docs/error-wrapping.md`)
12. [Exception Filtering](#doc-docs-exception-filtering) (`docs/exception-filtering.md`)
13. [Exception Groups](#doc-docs-exception-groups) (`docs/exception-groups.md`)
14. [Exception Notes](#doc-docs-exception-notes) (`docs/exception-notes.md`)
15. [Exception Transformation](#doc-docs-exception-transformation) (`docs/exception-transformation.md`)
16. [Result Integration](#doc-docs-result-integration) (`docs/result-integration.md`)
<a id="doc-docs-readme"></a>

Throw provides a fluent, readable API for conditionally throwing exceptions in Laravel applications.

## Requirements

Throw requires PHP 8.5+.

## Installation

Install Throw with composer:

```bash
composer require cline/throw
```

## Add the Trait

Add Throw's trait to your custom exception classes:

```php
use Cline\Throw\Concerns\ConditionallyThrowable;
use RuntimeException;

class InvalidTokenException extends RuntimeException
{
    use ConditionallyThrowable;

    public static function expired(): self
    {
        return new self('Token has expired');
    }
}
```

## Basic Usage

Now you can use fluent conditional throwing:

```php
// Throw if condition is true
InvalidTokenException::expired()->throwIf($token->isExpired());

// Throw unless condition is true
InvalidTokenException::expired()->throwUnless($token->isValid());
```

## Next Steps

- Learn about [Basic Usage](#doc-docs-basic-usage) patterns
- Explore [HTTP Responses](#doc-docs-http-responses) for aborting requests
- See [Integration Patterns](#doc-docs-integration-patterns) for Laravel conventions

<a id="doc-docs-basic-usage"></a>

The `ConditionallyThrowable` trait provides four core methods for conditional exception throwing. All methods support both boolean values and callbacks for lazy evaluation.

## throwIf()

Throws the exception when the condition evaluates to `true`:

```php
use App\Exceptions\ValidationException;

// Basic guard clause
ValidationException::invalidEmail()->throwIf(!filter_var($email, FILTER_VALIDATE_EMAIL));

// Null checking
MissingResourceException::notFound()->throwIf($user === null);

// Type validation
InvalidTypeException::expectedArray()->throwIf(!is_array($data));
```

### Real-World Example

```php
class UserService
{
    public function findOrFail(int $id): User
    {
        $user = User::find($id);

        UserNotFoundException::withId($id)->throwIf($user === null);

        return $user;
    }
}
```

## throwUnless()

Throws the exception when the condition evaluates to `false`:

```php
// Interface validation
InvalidTokenableException::mustImplementInterface()
    ->throwUnless($tokenable instanceof HasApiTokens);

// Permission checking
UnauthorizedException::missingPermission()
    ->throwUnless($user->can('admin'));

// State validation
InvalidStateException::notPublished()
    ->throwUnless($post->isPublished());
```

### Real-World Example

```php
class PaymentProcessor
{
    public function process(Order $order): void
    {
        PaymentException::orderNotPayable()
            ->throwUnless($order->canAcceptPayment());

        // Process payment...
    }
}
```

## Comparison with Laravel Helpers

### Before (using throw_if helper)

```php
throw_if($user === null, UserNotFoundException::withId($id));
throw_unless($user->can('admin'), UnauthorizedException::class);
```

### After (using Throw trait)

```php
UserNotFoundException::withId($id)->throwIf($user === null);
UnauthorizedException::missingPermission()->throwUnless($user->can('admin'));
```

### Readability Benefits

The fluent pattern offers several advantages:

1. **Left-to-right reading**: Exception details come first, then the condition
2. **Natural chaining**: Works seamlessly with static factory methods
3. **Better autocomplete**: IDEs suggest all available factory methods
4. **More explicit**: The exception type and message are immediately visible

## Chaining with Static Factories

Throw works beautifully with named constructors:

```php
final class AuthenticationException extends RuntimeException
{
    use ConditionallyThrowable;

    public static function invalidCredentials(): self
    {
        return new self('Invalid username or password');
    }

    public static function accountLocked(): self
    {
        return new self('Account has been locked due to too many failed attempts');
    }

    public static function sessionExpired(): self
    {
        return new self('Your session has expired. Please log in again');
    }
}

// Usage
AuthenticationException::invalidCredentials()->throwIf(!Hash::check($password, $user->password));
AuthenticationException::accountLocked()->throwIf($user->isLocked());
AuthenticationException::sessionExpired()->throwIf($session->isExpired());
```

## Complex Conditions

You can use any boolean expression:

```php
// Multiple conditions with &&
InvalidConfigurationException::missingRequiredFields()
    ->throwIf(empty($config['api_key']) && empty($config['secret']));

// Negation
InvalidStateException::unexpectedStatus()
    ->throwIf(!in_array($order->status, ['pending', 'processing']));

// Method calls
RateLimitException::tooManyAttempts()
    ->throwIf($this->rateLimiter->tooManyAttempts($key));
```

## Lazy Evaluation with Callbacks

All conditional methods support callbacks for lazy evaluation, which only execute when needed:

```php
// Expensive database check - only runs if needed
UserNotFoundException::notFound()
    ->throwIf(fn() => User::where('email', $email)->doesntExist());

// Complex permission check
UnauthorizedException::forbidden()
    ->throwUnless(fn() => $user->can('edit', $post) && !$post->isLocked());

// Rate limiting check
RateLimitException::exceeded()
    ->throwIf(fn() => !$this->rateLimiter->allow($key, 60));

// Multiple queries deferred
DataException::invalid()
    ->throwUnless(fn() => $this->validator->passes() && $this->exists($id));
```

### When to Use Callbacks

Use callbacks when:
- The condition involves expensive operations (database queries, API calls)
- The check should only run if previous conditions pass
- You want to defer evaluation for performance
- The condition has side effects you want to control

## Supporting Both Patterns

You can support both the traditional Laravel helper and the fluent API:

```php
// Traditional
throw_if($tokenable === null, MissingTokenableException::forParentToken());

// Fluent
MissingTokenableException::forParentToken()->throwIf($tokenable === null);
```

Both work identically - choose based on your team's preference or existing codebase conventions.

<a id="doc-docs-http-responses"></a>

The package provides multiple ways to abort HTTP requests with status codes: trait methods (`abortIf`, `abortUnless`), assertion helpers (`orAbort`, `orNotFound`, etc.), and a type-safe `HttpStatusCode` enum.

## HttpStatusCode Enum

The `HttpStatusCode` enum provides type-safe HTTP status codes with IDE autocomplete support:

```php
use Cline\Throw\Support\HttpStatusCode;

use function Cline\Thrownsure;

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
use function Cline\Thrownsure;

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
use App\Exceptions nauthorizedException;

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
use function Cline\Thrownsure;

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
use function Cline\Thrownsure;

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

<a id="doc-docs-integration-patterns"></a>

This guide demonstrates how to integrate the Throw package with common Laravel patterns and third-party services.

## Table of Contents
- [Assertion API (ensure)](#assertion-api-ensure)
- [Wrapping Laravel Exceptions](#wrapping-laravel-exceptions)
- [Form Request Validation](#form-request-validation)
- [Eloquent Model Guards](#eloquent-model-guards)
- [Service Classes](#service-classes)
- [API Resources](#api-resources)
- [Jobs and Queues](#jobs-and-queues)
- [Error Monitoring](#error-monitoring)
- [Third-Party API Clients](#third-party-api-clients)

## Assertion API (ensure)

The `ensure()` helper provides a fluent API for conditional throwing and aborting. It supports both boolean values and callbacks for lazy evaluation.

### Basic Usage

```php
use function Cline\Thrownsure;

// Boolean conditions
ensure($user !== null)->orThrow(UserNotFoundException::class);
ensure($user->isAdmin())->orForbidden();
ensure($token->isValid())->orUnauthorized();

// Callback for lazy evaluation (only evaluated when needed)
ensure(fn() => expensive_check())->orThrow(Exception::class);
ensure(fn() => $user->isAdmin())->orForbidden();
```

### Throw Methods

```php
use function Cline\Thrownsure;

// orThrow - throws when condition is FALSE
ensure($user !== null)->orThrow(UserNotFoundException::class);
ensure(false)->orThrow(RuntimeException::class, 'Failed'); // throws

// throwIf - throws when condition is TRUE
ensure(true)->throwIf(RuntimeException::class); // throws
ensure($user->isBanned())->throwIf(UserBannedException::class);

// throwUnless - throws when condition is FALSE (alias for orThrow)
ensure(false)->throwUnless(RuntimeException::class); // throws
ensure($token->isValid())->throwUnless(InvalidTokenException::class);
```

### Abort Methods (HTTP Status Codes)

```php
use Cline\Throw\Support\HttpStatusCode;
use function Cline\Thrownsure;

// orAbort - aborts when condition is FALSE
ensure($user->can('admin'))->orAbort(HttpStatusCode::Forbidden);
ensure(false)->orAbort(HttpStatusCode::NotFound); // aborts with 404

// abortIf - aborts when condition is TRUE
ensure(true)->abortIf(HttpStatusCode::BadRequest); // aborts with 400
ensure($user->isBanned())->abortIf(HttpStatusCode::Forbidden, 'Account banned');

// abortUnless - aborts when condition is FALSE (alias for orAbort)
ensure(false)->abortUnless(HttpStatusCode::NotFound); // aborts with 404
ensure($user !== null)->abortUnless(HttpStatusCode::NotFound);
```

### HTTP Helper Methods

Convenient shorthand for common HTTP status codes:

```php
use function Cline\Thrownsure;

// 4xx Client Errors
ensure($input->isValid())->orBadRequest('Invalid input');           // 400
ensure($token !== null)->orUnauthorized('Authentication required'); // 401
ensure($user->can('admin'))->orForbidden('Access denied');         // 403
ensure($post !== null)->orNotFound('Post not found');              // 404
ensure(!$user->exists())->orConflict('User already exists');       // 409
ensure($validation->passes())->orUnprocessable('Validation failed'); // 422
ensure($rateLimiter->allow())->orTooManyRequests();                // 429

// 5xx Server Errors
ensure($service->isHealthy())->orServerError();                    // 500
ensure(!$maintenance->isEnabled())->orServiceUnavailable();        // 503

// Additional helpers
ensure($request->isMethod('POST'))->orMethodNotAllowed();          // 405
ensure($request->accepts('json'))->orNotAcceptable();              // 406
ensure($request->timedOut())->orRequestTimeout();                  // 408
ensure(!$resource->isDeleted())->orGone('Resource deleted');       // 410
ensure($file->size() <= $max)->orPayloadTooLarge();               // 413
ensure($request->isJson())->orUnsupportedMediaType();             // 415
ensure(!$request->wantsCoffee())->orImATeapot();                  // 418
ensure(!$resource->isLocked())->orLocked('Resource locked');      // 423
ensure($hasHeader)->orPreconditionRequired();                      // 428
ensure($feature->isImplemented())->orNotImplemented();            // 501
ensure($upstream->isResponding())->orBadGateway();                // 502
ensure($upstream->respondedInTime())->orGatewayTimeout();         // 504
```

### Lazy Evaluation with Callbacks

Use callbacks to defer expensive checks until needed:

```php
use function Cline\Thrownsure;

// Expensive database query only runs if previous checks pass
ensure(fn() => User::where('email', $email)->exists())
    ->orThrow(UserNotFoundException::class);

// Complex permission check with multiple queries
ensure(fn() => $user->can('edit', $post) && !$post->isLocked())
    ->orForbidden('Cannot edit this post');

// Rate limiting check (closure only called if needed)
ensure(fn() => !RateLimiter::tooManyAttempts($key, 60))
    ->orTooManyRequests('Rate limit exceeded');
```

### Comparison: Assertion vs Exception Trait Methods

**Important:** The `ensure()` Assertion API has different semantics than Exception trait methods:

```php
use function Cline\Thrownsure;

// Assertion API (ensure helper)
ensure($condition)->throwIf($exception);     // throws when $condition is TRUE
ensure($condition)->throwUnless($exception); // throws when $condition is FALSE

// Exception Trait API (on exception instances)
Exception::create()->throwIf($condition);    // throws when $condition parameter is TRUE
Exception::create()->throwUnless($condition); // throws when $condition parameter is FALSE
```

## Wrapping Laravel Exceptions

Convert framework exceptions into domain-specific exceptions while preserving the original error.

```php
use Cline\Throw\Exceptions\DatabaseException;
use Illuminate\Database\QueryException;

class UserRepository
{
    public function find(int $id): User
    {
        try {
            return User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw UserNotFoundException::withId($id)->wrap($e);
        } catch (QueryException $e) {
            throw DatabaseException::queryFailed()
                ->wrap($e)
                ->withContext(['query' => $e->getSql()]);
        }
    }
}
```

## Form Request Validation

Use assertions in form requests for authorization and validation.

```php
use Cline\Throw\Support\HttpStatusCode;
use Illuminate\Foundation\Http\FormRequest;

use function Cline\Thrownsure;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');

        // Explicit authorization with typed status codes
        ensure($post->user_id === $this->user()->id)
            ->orForbidden('You cannot edit this post');

        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }
}
```

## Eloquent Model Guards

Add guard methods to your models for business rule enforcement.

```php
use Cline\Throw\Exceptions\BusinessRuleException;
use Illuminate\Database\Eloquent\Model;

use function Cline\Thrownsure;

class Subscription extends Model
{
    public function cancel(): void
    {
        ensure(!$this->isCancelled())
            ->orThrow(BusinessRuleException::subscriptionAlreadyCancelled());

        ensure($this->canBeCancelled())
            ->orThrow(BusinessRuleException::subscriptionNotCancellable());

        $this->update(['cancelled_at' => now()]);
    }

    public function charge(int $amount): void
    {
        ensure($this->isActive())
            ->orThrow(BusinessRuleException::subscriptionNotActive());

        ensure($amount > 0)
            ->orThrow(BusinessRuleException::invalidAmount());

        // Process charge...
    }
}
```

## Service Classes

Structure service classes with explicit error handling.

```php
use Cline\Throw\Exceptions\PaymentException;
use Cline\Throw\Support\Errors;
use Stripe\Exception\CardException;

use function Cline\Thrownsure;

class PaymentService
{
    public function charge(User $user, int $amount): Payment
    {
        ensure($amount > 0)
            ->orThrow(PaymentException::invalidAmount($amount));

        ensure($user->hasPaymentMethod())
            ->orThrow(PaymentException::noPaymentMethod());

        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount,
                'currency' => 'usd',
                'customer' => $user->stripe_id,
            ]);

            return Payment::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'stripe_charge_id' => $charge->id,
            ]);
        } catch (CardException $e) {
            throw PaymentException::cardDeclined()
                ->wrap($e)
                ->withContext([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'decline_code' => $e->getDeclineCode(),
                ]);
        }
    }
}
```

## API Resources

Guard against missing data in API resources.

```php
use Cline\Throw\Exceptions\ResourceException;
use Illuminate\Http\Resources\Json\JsonResource;

use function Cline\Thrownsure;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        ensure($this->resource !== null)
            ->orThrow(ResourceException::missingData());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile' => new ProfileResource($this->whenLoaded('profile')),
        ];
    }
}
```

## Jobs and Queues

Handle job failures with detailed error context.

```php
use Cline\Throw\Exceptions\ExternalServiceException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use function Cline\Thrownsure;

class ProcessWebhook implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public function __construct(
        private array $payload,
    ) {}

    public function handle(): void
    {
        ensure(isset($this->payload['event']))
            ->orThrow(ExternalServiceException::invalidWebhookPayload()
                ->withContext(['payload' => $this->payload]));

        match ($this->payload['event']) {
            'payment.succeeded' => $this->handlePaymentSucceeded(),
            'payment.failed' => $this->handlePaymentFailed(),
            default => throw ExternalServiceException::unknownWebhookEvent()
                ->withContext(['event' => $this->payload['event']]),
        };
    }

    public function failed(Throwable $exception): void
    {
        logger()->error('Webhook processing failed', [
            'exception' => $exception->getMessage(),
            'payload' => $this->payload,
            'context' => method_exists($exception, 'getContext')
                ? $exception->getContext()
                : [],
        ]);
    }
}
```

## Error Monitoring

Integrate with Sentry, Flare, or other monitoring services.

```php
use Cline\Throw\Exceptions\DomainException;
use Cline\Throw\Support\Errors;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Sentry\State\Scope;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e, function (Scope $scope) use ($e) {
                    // Add custom context from HasErrorContext trait
                    if (method_exists($e, 'getContext')) {
                        $scope->setContext('error_context', $e->getContext());
                    }

                    // Add tags from exception
                    if (method_exists($e, 'getTags')) {
                        foreach ($e->getTags() as $tag) {
                            $scope->setTag('custom', $tag);
                        }
                    }

                    // Add metadata
                    if (method_exists($e, 'getMetadata')) {
                        $scope->setContext('metadata', $e->getMetadata());
                    }

                    // Check for wrapped exceptions
                    if (method_exists($e, 'getWrapped') && $e->getWrapped()) {
                        $scope->setContext('wrapped_exception', [
                            'class' => get_class($e->getWrapped()),
                            'message' => $e->getWrapped()->getMessage(),
                        ]);
                    }
                });
            }
        });

        // Handle domain exceptions differently
        $this->renderable(function (DomainException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'context' => $e->getContext(),
                ], 422);
            }
        });
    }
}
```

## Third-Party API Clients

Wrap external service errors with domain exceptions.

```php
use Cline\Throw\Exceptions\ExternalServiceException;
use Cline\Throw\Support\Errors;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use function Cline\Thrownsure;

class GitHubClient
{
    public function __construct(
        private Client $client,
        private string $token,
    ) {}

    public function getUser(string $username): array
    {
        ensure($username !== '')
            ->orThrow(ExternalServiceException::invalidParameter('username'));

        try {
            $response = $this->client->get("https://api.github.com/users/{$username}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                    'Accept' => 'application/vnd.github.v3+json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            // Check if it's a 404
            if (Errors::is($e, RequestException::class) && $e->getResponse()?->getStatusCode() === 404) {
                throw ExternalServiceException::userNotFound($username)
                    ->wrap($e)
                    ->withTags(['github', 'not-found']);
            }

            // Check if it's rate limiting
            if ($e->getResponse()?->getStatusCode() === 429) {
                throw ExternalServiceException::rateLimitExceeded()
                    ->wrap($e)
                    ->withTags(['github', 'rate-limit'])
                    ->withMetadata([
                        'retry_after' => $e->getResponse()->getHeader('Retry-After'),
                    ]);
            }

            // Generic API error
            throw ExternalServiceException::apiRequestFailed()
                ->wrap($e)
                ->withContext([
                    'service' => 'github',
                    'endpoint' => "users/{$username}",
                    'status_code' => $e->getResponse()?->getStatusCode(),
                ]);
        }
    }
}
```

## Controller Integration

Clean controller actions with explicit error handling.

```php
use Cline\Throw\Support\HttpStatusCode;
use Illuminate\Http\JsonResponse;

use function Cline\Thrownsure;

class PostController extends Controller
{
    public function show(Post $post): JsonResponse
    {
        ensure($post->isPublished() || $post->user_id === auth()->id())
            ->orForbidden();

        return response()->json(new PostResource($post));
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        ensure($post->user_id === auth()->id())
            ->orForbidden('You cannot edit this post');

        ensure(!$post->isLocked())
            ->orAbort(HttpStatusCode::Conflict, 'Post is locked');

        $post->update($request->validated());

        return response()->json(new PostResource($post));
    }

    public function destroy(Post $post): JsonResponse
    {
        ensure($post->user_id === auth()->id())
            ->orForbidden();

        ensure($post->canBeDeleted())
            ->orAbort(HttpStatusCode::Conflict, 'Post cannot be deleted');

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
```

## Middleware Integration

Use assertions in middleware for request validation.

```php
use Cline\Throw\Support\HttpStatusCode;
use Closure;
use Illuminate\Http\Request;

use function Cline\Thrownsure;

class EnsureUserIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        ensure($request->user()?->hasVerifiedEmail())
            ->orAbort(HttpStatusCode::Forbidden, 'Email must be verified');

        return $next($request);
    }
}

class EnsureApiRateLimit
{
    public function handle(Request $request, Closure $next)
    {
        $limiter = app(RateLimiter::class);

        ensure($limiter->attempt($request->user()->id, 60, fn() => true))
            ->orTooManyRequests('Rate limit exceeded');

        return $next($request);
    }
}
```

<a id="doc-docs-assertions"></a>

Use the `ensure()` helper for fluent, readable guard clauses that throw exceptions or abort HTTP requests.

## Overview

The `ensure()` helper provides a clean alternative to traditional guard clauses:

```php
// Traditional approach
if ($user === null) {
    throw new UserNotFoundException();
}

// With ensure()
ensure($user !== null)->orThrow(UserNotFoundException::class);
```

## Basic Usage

### Throw Exceptions

```php
use function Cline\Throw\ensure;

// With exception class
ensure($user !== null)->orThrow(UserNotFoundException::class);

// With exception class and message
ensure($email !== null)->orThrow(ValidationException::class, 'Email is required');

// With exception instance
ensure($token->isValid())->orThrow(InvalidTokenException::expired());
```

### Abort HTTP Requests

```php
// Abort with status code
ensure($user->isAdmin())->orAbort(403);

// Abort with status code and message
ensure($post !== null)->orAbort(404, 'Post not found');
```

## Common Patterns

### Null Checks

```php
// Ensure value is not null
ensure($user !== null)->orThrow(UserNotFoundException::class);

// Ensure value exists
ensure(isset($data['email']))->orThrow(ValidationException::class, 'Email required');
```

### Type Validation

```php
// Check types
ensure(is_array($data))->orThrow(InvalidTypeException::class, 'Expected array');

ensure(is_string($email))->orThrow(InvalidTypeException::class, 'Email must be string');

ensure($user instanceof User)->orThrow(InvalidTypeException::class);
```

### Range Validation

```php
// Numeric ranges
ensure($age >= 18)->orThrow(ValidationException::class, 'Must be 18 or older');

ensure($quantity > 0 && $quantity <= 100)
    ->orThrow(ValidationException::class, 'Quantity must be between 1 and 100');
```

### String Validation

```php
// String length
ensure(strlen($password) >= 8)
    ->orThrow(ValidationException::class, 'Password must be at least 8 characters');

// String content
ensure(str_contains($email, '@'))
    ->orThrow(ValidationException::class, 'Invalid email format');
```

### Permission Checks

```php
// Authorization
ensure($user->can('edit', $post))
    ->orAbort(403, 'You cannot edit this post');

// Role checks
ensure($user->hasRole('admin'))
    ->orAbort(403, 'Admin access required');
```

## Real-World Examples

### Controller Usage

```php
class PostController extends Controller
{
    public function update(Request $request, int $id)
    {
        $post = Post::find($id);

        // Ensure post exists
        ensure($post !== null)->orAbort(404, 'Post not found');

        // Ensure user can edit
        ensure($request->user()->can('update', $post))
            ->orAbort(403, 'Cannot edit this post');

        $post->update($request->validated());

        return response()->json($post);
    }

    public function destroy(Request $request, int $id)
    {
        $post = Post::find($id);

        ensure($post !== null)->orAbort(404);
        ensure($request->user()->owns($post))->orAbort(403);

        $post->delete();

        return response()->noContent();
    }
}
```

### Service Layer

```php
class PaymentService
{
    public function processPayment(Order $order, PaymentMethod $method): Payment
    {
        // Business rule validation
        ensure($order->canAcceptPayment())
            ->orThrow(OrderException::cannotAcceptPayment());

        ensure($order->total->isPositive())
            ->orThrow(OrderException::invalidAmount());

        ensure($method->isValid())
            ->orThrow(PaymentMethodException::invalid());

        // Process payment...
    }
}
```

### Repository Layer

```php
class UserRepository
{
    public function findByEmail(string $email): User
    {
        ensure(!empty($email))
            ->orThrow(ValidationException::class, 'Email cannot be empty');

        ensure(filter_var($email, FILTER_VALIDATE_EMAIL))
            ->orThrow(ValidationException::class, 'Invalid email format');

        $user = User::where('email', $email)->first();

        ensure($user !== null)
            ->orThrow(UserNotFoundException::class);

        return $user;
    }
}
```

### Domain Models

```php
class Order
{
    public function cancel(): void
    {
        ensure($this->canBeCancelled())
            ->orThrow(OrderException::cannotCancel());

        ensure($this->status !== 'shipped')
            ->orThrow(OrderException::alreadyShipped());

        $this->status = 'cancelled';
        $this->save();
    }

    public function ship(): void
    {
        ensure($this->isPaid())
            ->orThrow(OrderException::notPaid());

        ensure($this->hasAddress())
            ->orThrow(OrderException::missingAddress());

        $this->status = 'shipped';
        $this->shipped_at = now();
        $this->save();
    }
}
```

### Middleware

```php
class RequireApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');

        // Ensure API key present
        ensure(!empty($apiKey))
            ->orAbort(401, 'API key required');

        // Ensure API key valid
        ensure($this->isValidApiKey($apiKey))
            ->orAbort(403, 'Invalid API key');

        return $next($request);
    }
}
```

### Form Requests

```php
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        ensure($this->user()->can('create-users'))
            ->orAbort(403);

        return true;
    }

    protected function prepareForValidation(): void
    {
        // Ensure required data exists
        ensure($this->has('email'))
            ->orThrow(ValidationException::class, 'Email is required');
    }
}
```

## Combining with Exception Features

### With Context

```php
ensure($account->balance >= $amount)
    ->orThrow(
        InsufficientFundsException::forAmount($amount)
            ->withContext([
                'account_id' => $account->id,
                'balance' => $account->balance,
                'required' => $amount,
            ])
    );
```

### With Tags

```php
ensure($subscription->isActive())
    ->orThrow(
        SubscriptionInactiveException::create()
            ->withTags(['subscription', 'access-control'])
    );
```

### With Wrapping

```php
try {
    $result = $this->api->call();
} catch (ApiException $e) {
    ensure($result !== null)
        ->orThrow(
            ExternalServiceException::failed()
                ->wrap($e)
                ->withTags(['external-api', 'critical'])
        );
}
```

## Multiple Assertions

Chain multiple assertions for comprehensive validation:

```php
public function createUser(array $data): User
{
    // Validate all inputs
    ensure(isset($data['email']))
        ->orThrow(ValidationException::class, 'Email required');

    ensure(isset($data['password']))
        ->orThrow(ValidationException::class, 'Password required');

    ensure(filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        ->orThrow(ValidationException::class, 'Invalid email');

    ensure(strlen($data['password']) >= 8)
        ->orThrow(ValidationException::class, 'Password too short');

    return User::create($data);
}
```

## Comparison with Alternatives

### vs throw_if()

```php
// Laravel's throw_if
throw_if($user === null, UserNotFoundException::class);

// ensure() - reads left to right
ensure($user !== null)->orThrow(UserNotFoundException::class);
```

### vs abort_if()

```php
// Laravel's abort_if
abort_if(!$user->isAdmin(), 403);

// ensure() - more explicit
ensure($user->isAdmin())->orAbort(403);
```

### vs Traditional Guards

```php
// Traditional
if ($user === null) {
    throw new UserNotFoundException();
}

// ensure() - more concise
ensure($user !== null)->orThrow(UserNotFoundException::class);
```

## When to Use ensure()

**Use `ensure()` when:**
- Writing guard clauses
- Validating preconditions
- Checking permissions/authorization
- Asserting business rules
- Validating input data

**Don't use `ensure()` when:**
- The condition is part of normal control flow
- You need complex error handling
- Multiple outcomes are valid
- You're checking for expected conditions (use if/else)

## Best Practices

1. **Keep conditions simple** - Complex conditions reduce readability
2. **Use descriptive messages** - Help debugging with clear error messages
3. **Fail early** - Place assertions at the start of methods
4. **Be explicit** - Prefer positive conditions (`!== null` vs `=== null`)
5. **Combine with exceptions** - Use factory methods for rich exception data

## Testing

```php
use function Cline\Throw\ensure;

test('throws when condition fails', function () {
    expect(fn () => ensure(false)->orThrow(RuntimeException::class))
        ->toThrow(RuntimeException::class);
});

test('aborts when condition fails', function () {
    expect(fn () => ensure(false)->orAbort(404))
        ->toThrow(HttpException::class);
});

test('does not throw when condition passes', function () {
    ensure(true)->orThrow(RuntimeException::class);

    expect(true)->toBeTrue();
});
```

## Next Steps

- Learn about [Base Exceptions](#doc-docs-base-exceptions) for creating domain-specific exceptions
- Explore [Error Context](#doc-docs-error-context) for adding debugging information
- See [Error Wrapping](#doc-docs-error-wrapping) for exception chains

<a id="doc-docs-attempt-monad"></a>

The `attempt()` helper provides a Scala-inspired Try monad for handling exceptions fluently. It wraps code execution and lets you handle success/failure with various strategies.

## Core Concepts

### Success vs Failure

The `Attempt` class represents a computation that may either succeed (Success) or fail (Failure). Unlike traditional try-catch, you handle both cases fluently:

```php
use function Cline\Throw\attempt;

// Execute and get result
$user = attempt(fn() => User::findOrFail($id))->get();

// Execute with fallback
$user = attempt(fn() => User::find($id))->getOrElse(null);

// Convert to Option-like (null on failure)
$user = attempt(fn() => loadUser())->toOption();

// Recover from failure
$data = attempt(fn() => fetchFromApi())->recover(fn($e) => getCached());
```

## Scala-Style Methods

### `get()`

Unwrap the result or throw the original exception:

```php
// Throws original exception if it fails
$user = attempt(fn() => User::findOrFail($id))->get();

// Equivalent to traditional:
try {
    $user = User::findOrFail($id);
} catch (Throwable $e) {
    throw $e;
}
```

### `getOrElse()`

Get result or return a default value:

```php
// Return null if not found
$user = attempt(fn() => User::find($id))->getOrElse(null);

// Return empty array
$items = attempt(fn() => fetchItems())->getOrElse([]);

// Return default object
$config = attempt(fn() => loadConfig())->getOrElse(new Config());
```

### `toOption()`

Convert to Option monad (`Some<T>` or `None`):

```php
use Cline\Monad\Option\Option;

// Returns Some<User> or None
$user = attempt(fn() => loadUser())->toOption();

// Chain with Option methods
$name = attempt(fn() => $user->profile->name)
    ->toOption()
    ->map(fn($n) => strtoupper($n))
    ->unwrapOr('GUEST');

// Use with Option's unwrapOr
$result = attempt(fn() => dangerousOp())
    ->toOption()
    ->unwrapOr('default');

// Transform with map/filter
$email = attempt(fn() => $user->email)
    ->toOption()
    ->filter(fn($e) => str_contains($e, '@'))
    ->map(fn($e) => strtolower($e))
    ->unwrapOr('no-email@example.com');
```

### `recover()`

Execute a callback to recover from failure:

```php
// Recover with cached data
$data = attempt(fn() => fetchFromApi())
    ->recover(fn($e) => Cache::get('data', []));

// Log and return fallback
$result = attempt(fn() => processPayment($order))
    ->recover(function (Throwable $e) {
        Log::error('Payment failed', ['error' => $e->getMessage()]);
        return ['status' => 'pending'];
    });

// Transform exception into value
$status = attempt(fn() => checkService())
    ->recover(fn($e) => 'offline');
```

## Custom Exception Handling

### `orThrow()`

Throw a custom exception instead of the original:

```php
// Throw custom exception class
attempt(fn() => parseJson($data))
    ->orThrow(InvalidJsonException::class, 'Invalid JSON provided');

// Throw exception instance
attempt(fn() => loadResource($id))
    ->orThrow(ResourceNotFoundException::notFound($id));

// Original exception is wrapped as previous
try {
    attempt(fn() => dangerousOp())
        ->orThrow(ServiceException::class, 'Service failed');
} catch (ServiceException $e) {
    $original = $e->getPrevious(); // Original exception
}
```

## HTTP Abort Helpers

### `abort()`

Abort HTTP request with status code:

```php
use Cline\Throw\Support\HttpStatusCode;

attempt(fn() => authorize($user))
    ->abort(HttpStatusCode::Forbidden, 'Access denied');
```

### Convenience Helpers

```php
// 400 Bad Request
attempt(fn() => validateInput($data))
    ->orBadRequest('Invalid input');

// 401 Unauthorized
attempt(fn() => authenticate($token))
    ->orUnauthorized('Authentication required');

// 403 Forbidden
attempt(fn() => checkPermission($user, 'admin'))
    ->orForbidden();

// 404 Not Found
attempt(fn() => Post::findOrFail($id))
    ->orNotFound('Post not found');

// 409 Conflict
attempt(fn() => createUser($email))
    ->orConflict('Email already exists');

// 422 Unprocessable Entity
attempt(fn() => validator($data)->validate())
    ->orUnprocessable('Validation failed');

// 429 Too Many Requests
attempt(fn() => rateLimiter()->attempt($key))
    ->orTooManyRequests();

// 500 Internal Server Error
attempt(fn() => criticalOperation())
    ->orServerError();
```

## Executing Different Callables

### Closures

```php
$result = attempt(fn() => expensiveComputation())->get();
```

### Invokable Classes

```php
class ProcessPayment
{
    public function __invoke(Order $order): PaymentResult
    {
        // Process payment
    }
}

$result = attempt(ProcessPayment::class)->getOrElse(null);
$result = attempt(new ProcessPayment())->get();
```

### Classes with `handle()` Method

```php
class ProcessOrder
{
    public function handle(): OrderResult
    {
        // Process order
    }
}

$result = attempt(new ProcessOrder())->get();
```

### Callable Arrays

```php
// Static methods
$result = attempt([User::class, 'findByEmail'])
    ->getOrElse(null);

// Instance methods
$service = new ApiService();
$data = attempt([$service, 'fetchData'])
    ->recover(fn($e) => []);
```

## Real-World Examples

### API Calls with Fallbacks

```php
// Try API, fall back to cache, then default
$data = attempt(fn() => Http::get($url)->json())
    ->recover(fn($e) => Cache::get("api:{$url}"))
    ?? [];
```

### Safe Property Access

```php
// Deeply nested property access with Option
$city = attempt(fn() => $user->address->location->city)
    ->toOption()
    ->unwrapOr('Unknown');

// Chain transformations
$displayName = attempt(fn() => $user->profile->fullName)
    ->toOption()
    ->map(fn($name) => trim($name))
    ->filter(fn($name) => $name !== '')
    ->unwrapOr('Anonymous');
```

### Database Operations

```php
// Find or create pattern
$user = attempt(fn() => User::where('email', $email)->firstOrFail())
    ->recover(fn($e) => User::create(['email' => $email]));
```

### File Operations

```php
// Read file with fallback
$content = attempt(fn() => file_get_contents($path))
    ->getOrElse('');

// Parse JSON safely
$data = attempt(fn() => json_decode($json, true, flags: JSON_THROW_ON_ERROR))
    ->recover(fn($e) => []);
```

### Service Layer

```php
class PaymentService
{
    public function charge(Order $order): PaymentResult
    {
        return attempt(fn() => $this->gateway->charge($order))
            ->recover(function (Throwable $e) use ($order) {
                Log::error('Payment failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);

                return PaymentResult::failed($e->getMessage());
            });
    }
}
```

### Controller Actions

```php
class PostController
{
    public function show(int $id)
    {
        $post = attempt(fn() => Post::findOrFail($id))
            ->orNotFound('Post not found');

        return view('posts.show', compact('post'));
    }

    public function update(Request $request, int $id)
    {
        attempt(fn() => $this->authorize('update', Post::findOrFail($id)))
            ->orForbidden();

        $post = attempt(fn() => $this->postService->update($id, $request->validated()))
            ->orServerError('Failed to update post');

        return redirect()->route('posts.show', $post);
    }
}
```

### Multi-Step Operations

```php
// Chain multiple risky operations
$result = attempt(fn() => $this->validateData($input))
    ->recover(fn($e) => throw ValidationException::invalid($e->getMessage()));

$processed = attempt(fn() => $this->processData($result))
    ->recover(fn($e) => $this->fallbackProcessor($result));

$saved = attempt(fn() => $this->saveData($processed))
    ->orThrow(PersistenceException::class, 'Failed to save');
```

## Pattern Comparison

### Traditional Try-Catch

```php
try {
    $user = User::findOrFail($id);
} catch (ModelNotFoundException $e) {
    $user = null;
}
```

### With Attempt + Option

```php
$user = attempt(fn() => User::findOrFail($id))
    ->toOption()
    ->unwrapOr(null);

// Or use the Option directly
$user = attempt(fn() => User::findOrFail($id))->toOption();
// Returns Some<User> or None
```

### Traditional Try-Catch with Custom Exception

```php
try {
    $data = fetchFromApi();
} catch (Throwable $e) {
    Log::error('API failed', ['error' => $e->getMessage()]);
    throw new ServiceException('Service unavailable', previous: $e);
}
```

### With Attempt

```php
$data = attempt(fn() => fetchFromApi())
    ->recover(function ($e) {
        Log::error('API failed', ['error' => $e->getMessage()]);
        throw new ServiceException('Service unavailable', previous: $e);
    });
```

## Best Practices

### 1. Use `get()` for Pure Unwrapping

When you want to propagate the original exception:

```php
$user = attempt(fn() => User::findOrFail($id))->get();
```

### 2. Use `getOrElse()` for Safe Defaults

When you have a reasonable default value:

```php
$config = attempt(fn() => loadConfig())->getOrElse([]);
```

### 3. Use `toOption()` for Option Monad Chaining

When you want to leverage Option's map/filter/flatMap:

```php
$name = attempt(fn() => $user->name)
    ->toOption()
    ->map(fn($n) => strtoupper($n))
    ->unwrapOr('GUEST');

// Complex transformations
$validEmail = attempt(fn() => $user->email)
    ->toOption()
    ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
    ->map(fn($e) => strtolower($e))
    ->unwrapOr(null);
```

### 4. Use `recover()` for Side Effects

When you need to log, notify, or transform errors:

```php
attempt(fn() => sendEmail($user))
    ->recover(function ($e) use ($user) {
        Log::warning('Email failed', ['user' => $user->id]);
        Queue::push(new SendEmailJob($user));
    });
```

### 5. Use `orThrow()` for Custom Exceptions

When you want to transform exceptions into your domain:

```php
attempt(fn() => $this->externalApi->call())
    ->orThrow(ServiceUnavailableException::class);
```

### 6. Use HTTP Helpers in Controllers

Keep controller code clean and expressive:

```php
public function destroy(int $id)
{
    $post = attempt(fn() => Post::findOrFail($id))
        ->orNotFound();

    attempt(fn() => $this->authorize('delete', $post))
        ->orForbidden();

    attempt(fn() => $post->delete())
        ->orServerError();

    return redirect()->route('posts.index');
}
```

## Type Safety

The `Attempt` class is fully typed with generics:

```php
/** @var Attempt<User> */
$userAttempt = attempt(fn() => User::find($id));

/** @var User|null */
$user = $userAttempt->toOption();

/** @var User */
$user = $userAttempt->get(); // Throws if failed

/** @var User|Guest */
$user = $userAttempt->getOrElse(new Guest());
```

<a id="doc-docs-base-exceptions"></a>

Throw provides three base exception classes that categorize errors by their nature, making error handling more semantic and maintainable.

## Overview

The base exceptions help you organize errors into clear categories:

- **DomainException** - Business logic violations
- **InfrastructureException** - External system failures
- **ValidationException** - Input validation errors

All base exceptions include the `ConditionallyThrowable`, `HasErrorContext`, and `WrapsErrors` traits.

## DomainException

Use `DomainException` for business rule violations and domain-specific errors.

### Creating Domain Exceptions

```php
use Cline\Throw\Exceptions\DomainException;

final class OrderCannotBeCancelledException extends DomainException
{
    public static function alreadyShipped(): self
    {
        return new self('Order cannot be cancelled after shipping');
    }

    public static function alreadyDelivered(): self
    {
        return new self('Order has already been delivered');
    }
}
```

### Usage Examples

```php
// Guard against invalid state transitions
OrderCannotBeCancelledException::alreadyShipped()
    ->throwIf($order->status === 'shipped');

// Business rule enforcement
InsufficientFundsException::forAmount($amount)
    ->withContext(['balance' => $account->balance, 'required' => $amount])
    ->throwIf($account->balance < $amount);

// Invalid operations
SubscriptionNotActiveException::cannotAccess()
    ->throwUnless($subscription->isActive());
```

### Common Domain Exception Patterns

```php
// State machine violations
final class InvalidStateTransitionException extends DomainException
{
    public static function from(string $from, string $to): self
    {
        return new self("Cannot transition from {$from} to {$to}");
    }
}

// Business constraints
final class MaximumRetriesExceededException extends DomainException
{
    public static function forOperation(string $operation, int $max): self
    {
        return new self("Maximum {$max} retries exceeded for {$operation}");
    }
}

// Invariant violations
final class AccountOverdraftException extends DomainException
{
    public static function amount(Money $overdraft): self
    {
        return new self("Account would be overdrawn by {$overdraft->format()}");
    }
}
```

## InfrastructureException

Use `InfrastructureException` for failures in external dependencies like databases, APIs, file systems, or caches.

### Creating Infrastructure Exceptions

```php
use Cline\Throw\Exceptions\InfrastructureException;

final class DatabaseException extends InfrastructureException
{
    public static function connectionFailed(string $host): self
    {
        return new self("Failed to connect to database at {$host}");
    }

    public static function queryFailed(): self
    {
        return new self('Database query failed');
    }
}
```

### Usage Examples

```php
// Database operations
try {
    $db->query($sql);
} catch (PDOException $e) {
    throw DatabaseException::queryFailed()
        ->wrap($e)
        ->withContext(['query' => $sql, 'bindings' => $bindings]);
}

// External API calls
try {
    $response = $client->post('/payments', $data);
} catch (RequestException $e) {
    throw PaymentGatewayException::requestFailed()
        ->wrap($e)
        ->withTags(['payment', 'stripe', 'critical'])
        ->withMetadata(['data' => $data, 'response' => $e->getResponse()]);
}

// File system operations
FileSystemException::cannotWrite($path)
    ->throwIf(!is_writable($path));
```

### Common Infrastructure Exception Patterns

```php
// API failures
final class ApiException extends InfrastructureException
{
    public static function timeout(string $endpoint): self
    {
        return new self("API request to {$endpoint} timed out");
    }

    public static function rateLimited(): self
    {
        return new self('API rate limit exceeded');
    }
}

// Cache failures
final class CacheException extends InfrastructureException
{
    public static function connectionFailed(string $driver): self
    {
        return new self("Failed to connect to {$driver} cache");
    }
}

// Queue failures
final class QueueException extends InfrastructureException
{
    public static function jobFailed(string $job): self
    {
        return new self("Queue job {$job} failed to process");
    }
}
```

## ValidationException

Use `ValidationException` for input validation failures and data constraint violations.

### Creating Validation Exceptions

```php
use Cline\Throw\Exceptions\ValidationException;

final class InvalidEmailException extends ValidationException
{
    public static function format(string $email): self
    {
        return new self("Invalid email format: {$email}");
    }
}

final class RequiredFieldException extends ValidationException
{
    public static function missing(string $field): self
    {
        return new self("Required field missing: {$field}");
    }
}
```

### Usage Examples

```php
// Email validation
InvalidEmailException::format($email)
    ->throwIf(!filter_var($email, FILTER_VALIDATE_EMAIL));

// Required fields
RequiredFieldException::missing('email')
    ->throwIf(empty($data['email']));

// Type validation
InvalidTypeException::expectedArray('settings')
    ->withContext(['actual_type' => get_debug_type($value)])
    ->throwIf(!is_array($value));

// Range validation
OutOfRangeException::forField('age', 0, 120)
    ->throwIf($age < 0 || $age > 120);
```

### Common Validation Exception Patterns

```php
// Format validation
final class InvalidFormatException extends ValidationException
{
    public static function forField(string $field, string $expectedFormat): self
    {
        return new self("{$field} must be in {$expectedFormat} format");
    }
}

// Length validation
final class StringTooLongException extends ValidationException
{
    public static function forField(string $field, int $max, int $actual): self
    {
        return new self("{$field} exceeds maximum length of {$max} (got {$actual})");
    }
}

// Constraint validation
final class UniqueConstraintException extends ValidationException
{
    public static function forField(string $field, mixed $value): self
    {
        return new self("{$field} value '{$value}' already exists");
    }
}
```

## Choosing the Right Base Exception

| Exception Type | Use When | Examples |
|---------------|----------|----------|
| **DomainException** | Violating business rules or domain constraints | Order already shipped, insufficient funds, subscription expired |
| **InfrastructureException** | External systems fail | Database down, API timeout, file not writable |
| **ValidationException** | Input data is invalid | Invalid email, missing required field, number out of range |

## Combined Features

All base exceptions support the full Throw feature set:

```php
// Combine all features
PaymentFailedException::insufficientFunds()
    ->withContext([
        'user_id' => $user->id,
        'amount' => $amount,
        'balance' => $account->balance,
    ])
    ->withTags(['payment', 'critical', 'stripe'])
    ->withMetadata([
        'gateway_response' => $response,
        'attempt_number' => 3,
    ])
    ->throwIf($account->balance < $amount);

// Wrap lower-level exceptions
try {
    $db->query($sql);
} catch (PDOException $e) {
    throw DatabaseException::queryFailed()
        ->wrap($e)
        ->withContext(['query' => $sql])
        ->withTags(['database', 'critical']);
}
```

## Best Practices

1. **Choose the right base** - Use the exception type that best describes the error's nature
2. **Create specific exceptions** - Extend base exceptions for each distinct error case
3. **Use static factories** - Provide named constructors for common scenarios
4. **Add context** - Include relevant data to aid debugging
5. **Tag appropriately** - Use tags for filtering in monitoring systems
6. **Wrap low-level errors** - Preserve original exceptions while providing domain context

## Next Steps

- Learn about [Error Context](#doc-docs-error-context) for adding debugging information
- Explore [Error Wrapping](#doc-docs-error-wrapping) for preserving exception chains
- See [Assertions](#doc-docs-assertions) for the `ensure()` helper pattern

<a id="doc-docs-deferred-cleanup"></a>

Zig-inspired deferred cleanup that executes only when errors occur, ensuring resources are properly cleaned up on error paths.

## Overview

Deferred cleanup allows you to register callbacks that execute only when an exception is thrown, inspired by Zig's `errdefer`. This eliminates the need for explicit try-catch blocks for cleanup while ensuring resources are released on error paths.

```php
$cleanup = errdefer();
$cleanup->onError(fn() => DB::rollBack());

DB::beginTransaction();
$user = User::create($data); // If this throws, rollback runs automatically
DB::commit();
```

## Basic Usage

### Registering Cleanup Callbacks

```php
use function Cline\Throw\errdefer;

$cleanup = errdefer();

// Register cleanup that runs on error
$cleanup->onError(function() {
    fclose($fileHandle);
});
```

### Using with run()

```php
$cleanup = errdefer();
$cleanup->onError(fn() => unlink($tempFile));

$result = $cleanup->run(function() {
    // If this throws, temp file is deleted
    return processFile($tempFile);
});
```

### Manual Cleanup Trigger

```php
$cleanup = errdefer();
$cleanup->onError(fn() => rollbackTransaction());

if ($error) {
    $cleanup->cleanup(); // Manually trigger cleanup
}
```

## Real-World Examples

### Database Transactions

```php
class OrderService
{
    public function createOrder(array $data): Order
    {
        $cleanup = errdefer();
        $cleanup->onError(fn() => DB::rollBack());

        DB::beginTransaction();

        $order = Order::create($data);

        foreach ($data['items'] as $item) {
            $order->items()->create($item);
        }

        DB::commit();

        return $order;
    }
}
```

### File Upload with Cleanup

```php
class FileUploader
{
    public function upload(UploadedFile $file): string
    {
        $cleanup = errdefer();

        // Upload to temporary location
        $tempPath = $file->storeAs('temp', $file->hashName());
        $cleanup->onError(fn() => Storage::delete($tempPath));

        // Validate file
        $this->validate($tempPath);

        // Process file
        $processedPath = $this->process($tempPath);
        $cleanup->onError(fn() => Storage::delete($processedPath));

        // Move to final location
        $finalPath = $this->moveToFinal($processedPath);

        // Success - cleanup won't run
        return $finalPath;
    }
}
```

### Resource Management

```php
class ResourceManager
{
    public function processWithResources(): mixed
    {
        $cleanup = errdefer();

        $file = fopen('data.txt', 'w');
        $cleanup->onError(fn() => fclose($file));

        $lock = $this->acquireLock('processing');
        $cleanup->onError(fn() => $this->releaseLock($lock));

        $connection = $this->openConnection();
        $cleanup->onError(fn() => $connection->close());

        // Do work - if exception occurs, all resources are cleaned up
        return $this->processData($file, $connection);
    }
}
```

### API Client with Cleanup

```php
class ApiClient
{
    public function fetchWithRetry(string $endpoint): array
    {
        $cleanup = errdefer();

        $tempFile = tmpfile();
        $cleanup->onError(function() use ($tempFile) {
            if (is_resource($tempFile)) {
                fclose($tempFile);
            }
        });

        $response = Http::timeout(30)
            ->get($endpoint)
            ->throw();

        fwrite($tempFile, $response->body());

        return $this->processResponse($tempFile);
    }
}
```

### Multi-Step Operation

```php
class DeploymentService
{
    public function deploy(string $version): void
    {
        $cleanup = errdefer();

        // Step 1: Backup current version
        $backupPath = $this->backup();
        $cleanup->onError(fn() => $this->restore($backupPath));

        // Step 2: Download new version
        $downloadPath = $this->download($version);
        $cleanup->onError(fn() => unlink($downloadPath));

        // Step 3: Extract
        $extractPath = $this->extract($downloadPath);
        $cleanup->onError(fn() => $this->removeDirectory($extractPath));

        // Step 4: Deploy
        $this->installNewVersion($extractPath);

        // Success - no cleanup needed
    }
}
```

### Cache with Cleanup

```php
class CacheWriter
{
    public function writeToCache(string $key, mixed $data): void
    {
        $cleanup = errdefer();

        // Acquire write lock
        $lock = Cache::lock("write:{$key}", 10);
        $lock->get();
        $cleanup->onError(fn() => $lock->release());

        // Write to temp key first
        $tempKey = "{$key}:temp";
        Cache::put($tempKey, $data, 60);
        $cleanup->onError(fn() => Cache::forget($tempKey));

        // Validate data
        $this->validate(Cache::get($tempKey));

        // Swap to final key
        Cache::put($key, $data, 3600);
        Cache::forget($tempKey);

        $lock->release();
    }
}
```

### Background Job with Cleanup

```php
class ProcessOrderJob implements ShouldQueue
{
    public function handle(): void
    {
        $cleanup = errdefer();

        // Lock the order
        $lock = Cache::lock("order:{$this->orderId}", 300);
        $lock->get();
        $cleanup->onError(fn() => $lock->release());

        $order = Order::find($this->orderId);
        $order->update(['status' => 'processing']);
        $cleanup->onError(fn() => $order->update(['status' => 'pending']));

        // Process payment
        $this->processPayment($order);

        // Send confirmation
        $this->sendConfirmation($order);

        $order->update(['status' => 'completed']);
        $lock->release();
    }
}
```

### Nested Operations

```php
class UserRegistration
{
    public function register(array $data): User
    {
        $outerCleanup = errdefer();

        return $outerCleanup->run(function() use ($data) {
            DB::beginTransaction();
            $outerCleanup->onError(fn() => DB::rollBack());

            $user = User::create($data);

            // Nested operation with its own cleanup
            $innerCleanup = errdefer();
            $innerCleanup->run(function() use ($user) {
                $profile = $user->profile()->create([
                    'display_name' => $user->name,
                ]);

                $this->uploadAvatar($user, $profile);
            });

            DB::commit();

            return $user;
        });
    }
}
```

### Cleanup Execution Order

```php
class OrderProcessor
{
    public function process(Order $order): void
    {
        $cleanup = errdefer();

        // Cleanup executes in reverse order (LIFO)
        $cleanup->onError(fn() => Log::info('Cleanup step 1'));
        $cleanup->onError(fn() => Log::info('Cleanup step 2'));
        $cleanup->onError(fn() => Log::info('Cleanup step 3'));

        throw new RuntimeException('Error');

        // Output on error:
        // Cleanup step 3
        // Cleanup step 2
        // Cleanup step 1
    }
}
```

### Using with Attempt

```php
use function Cline\Throw\attempt;
use function Cline\Throw\errdefer;

class FileProcessor
{
    public function processFile(string $path): Result
    {
        $cleanup = errdefer();

        return attempt(function() use ($path, $cleanup) {
            $handle = fopen($path, 'r');
            $cleanup->onError(fn() => fclose($handle));

            $data = $this->readData($handle);
            $processed = $this->processData($data);

            fclose($handle);

            return $processed;
        })->toResult();
    }
}
```

## Advanced Patterns

### Conditional Cleanup

```php
$cleanup = errdefer();
$resourceAcquired = false;

$resource = $this->acquireResource();
$resourceAcquired = true;
$cleanup->onError(function() use (&$resourceAcquired, $resource) {
    if ($resourceAcquired) {
        $resource->release();
    }
});
```

### Cleanup with State

```php
class StatefulProcessor
{
    private array $acquiredResources = [];

    public function process(): void
    {
        $cleanup = errdefer();
        $cleanup->onError(fn() => $this->cleanupAll());

        foreach ($this->steps as $step) {
            $resource = $this->acquireResource($step);
            $this->acquiredResources[] = $resource;

            $this->executeStep($step, $resource);
        }

        // Success - clear acquired resources
        $this->acquiredResources = [];
    }

    private function cleanupAll(): void
    {
        foreach ($this->acquiredResources as $resource) {
            $resource->release();
        }

        $this->acquiredResources = [];
    }
}
```

### Cleanup Logging

```php
$cleanup = errdefer();

$cleanup->onError(function() {
    Log::warning('Operation failed, running cleanup', [
        'timestamp' => now(),
        'user_id' => auth()->id(),
    ]);

    $this->performCleanup();

    Log::info('Cleanup completed');
});
```

## Comparison with Try-Catch

### Traditional Approach

```php
try {
    DB::beginTransaction();

    $user = User::create($data);
    $profile = $user->profile()->create($profileData);

    DB::commit();
} catch (Throwable $e) {
    DB::rollBack();
    throw $e;
}
```

### Deferred Cleanup Approach

```php
$cleanup = errdefer();
$cleanup->onError(fn() => DB::rollBack());

DB::beginTransaction();
$user = User::create($data);
$profile = $user->profile()->create($profileData);
DB::commit();
```

## Best Practices

1. **Register cleanup immediately** - Right after acquiring a resource
2. **LIFO order** - Cleanup executes in reverse order of registration
3. **Idempotent cleanup** - Cleanup should be safe to run multiple times
4. **Single responsibility** - One cleanup callback per resource
5. **Use with run()** - For automatic cleanup on exceptions
6. **Manual cleanup** - Call `cleanup()` explicitly when needed
7. **Don't mix patterns** - Use either `run()` or destructor, not both

## See Also

- [Error Wrapping](#doc-docs-error-wrapping) - Wrapping lower-level exceptions
- [Exception Notes](#doc-docs-exception-notes) - Breadcrumb-style debugging
- [Result Integration](#doc-docs-result-integration) - Converting attempts to Result types

<a id="doc-docs-error-comparison"></a>

Go-inspired utilities for checking and casting exceptions through the exception chain.

## Overview

The `Errors` utility class provides two static methods inspired by Go's error handling:

- `Errors::is()` - Check if an exception matches a specific type
- `Errors::as()` - Cast an exception to a specific type

Both methods traverse the entire exception chain (via `getPrevious()`), making them useful for working with wrapped exceptions.

## Errors::is()

Check if an exception or any exception in its chain matches the given type.

### Basic Usage

```php
use Cline\Throw\Support\Errors;

try {
    $user->charge($amount);
} catch (Throwable $e) {
    if (Errors::is($e, PaymentException::class)) {
        // Handle payment error
    }
}
```

### Checking Wrapped Exceptions

```php
use Cline\Throw\Exceptions\DatabaseException;

try {
    $db->query($sql);
} catch (Throwable $e) {
    // Will match even if PDOException is wrapped in DatabaseException
    if (Errors::is($e, PDOException::class)) {
        logger()->error('Database connection failed');
        return response()->json(['error' => 'Database error'], 500);
    }
}
```

### Multiple Exception Checks

```php
try {
    $this->externalApi->call();
} catch (Throwable $e) {
    if (Errors::is($e, TimeoutException::class)) {
        return $this->handleTimeout($e);
    }

    if (Errors::is($e, RateLimitException::class)) {
        return $this->handleRateLimit($e);
    }

    if (Errors::is($e, NetworkException::class)) {
        return $this->handleNetworkError($e);
    }

    throw $e; // Re-throw if not handled
}
```

## Errors::as()

Cast an exception to a specific type if it matches, returning the typed exception or null.

### Basic Usage

```php
use Cline\Throw\Support\Errors;

try {
    $user->charge($amount);
} catch (Throwable $e) {
    $paymentError = Errors::as($e, PaymentException::class);

    if ($paymentError !== null) {
        logger()->error('Payment failed', [
            'transaction_id' => $paymentError->getTransactionId(),
            'amount' => $paymentError->getAmount(),
        ]);
    }
}
```

### Accessing Wrapped Exception Details

```php
try {
    $db->query($sql);
} catch (Throwable $e) {
    $pdoError = Errors::as($e, PDOException::class);

    if ($pdoError !== null) {
        logger()->error('Query failed', [
            'error_code' => $pdoError->getCode(),
            'sql_state' => $pdoError->errorInfo[0] ?? null,
        ]);
    }
}
```

### Using Exception Context

```php
try {
    $payment->process();
} catch (Throwable $e) {
    $domainError = Errors::as($e, DomainException::class);

    if ($domainError !== null) {
        // Access context from HasErrorContext trait
        $context = $domainError->getContext();
        $tags = $domainError->getTags();

        sentry()->captureException($domainError, [
            'context' => $context,
            'tags' => $tags,
        ]);
    }
}
```

## Comparison with Traditional Instanceof

### Before (using instanceof)

```php
try {
    $api->call();
} catch (Throwable $e) {
    // Only checks the top-level exception
    if ($e instanceof TimeoutException) {
        // Won't match if TimeoutException is wrapped
    }

    // To check wrapped exceptions, you need manual traversal
    $current = $e;
    while ($current !== null) {
        if ($current instanceof TimeoutException) {
            // Handle timeout
            break;
        }
        $current = $current->getPrevious();
    }
}
```

### After (using Errors::is)

```php
use Cline\Throw\Support\Errors;

try {
    $api->call();
} catch (Throwable $e) {
    // Automatically checks the entire exception chain
    if (Errors::is($e, TimeoutException::class)) {
        // Matches even if wrapped
    }
}
```

## Real-World Examples

### Exception Handler Integration

```php
use Cline\Throw\Support\Errors;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Handle domain exceptions differently
            if (Errors::is($e, DomainException::class)) {
                $domainError = Errors::as($e, DomainException::class);

                logger()->warning('Domain error', [
                    'message' => $domainError->getMessage(),
                    'context' => $domainError->getContext(),
                ]);

                return; // Don't report to error tracking
            }

            // Report infrastructure errors to Sentry
            if (Errors::is($e, InfrastructureException::class)) {
                app('sentry')->captureException($e);
            }
        });
    }
}
```

### Service Class Error Handling

```php
use Cline\Throw\Support\Errors;

class PaymentService
{
    public function charge(User $user, int $amount): Payment
    {
        try {
            return $this->gateway->charge($user->payment_method, $amount);
        } catch (Throwable $e) {
            // Check for specific gateway errors
            $cardError = Errors::as($e, CardException::class);
            if ($cardError !== null) {
                throw PaymentException::cardDeclined()
                    ->wrap($cardError)
                    ->withContext([
                        'decline_code' => $cardError->getDeclineCode(),
                        'user_id' => $user->id,
                    ]);
            }

            // Check for rate limiting
            if (Errors::is($e, RateLimitException::class)) {
                throw PaymentException::rateLimited()
                    ->wrap($e)
                    ->withTags(['payment', 'rate-limit']);
            }

            // Generic error
            throw PaymentException::failed()->wrap($e);
        }
    }
}
```

### API Client Error Mapping

```php
use Cline\Throw\Support\Errors;
use GuzzleHttp\Exception\RequestException;

class GitHubClient
{
    public function getUser(string $username): array
    {
        try {
            $response = $this->client->get("users/{$username}");
            return json_decode($response->getBody(), true);
        } catch (Throwable $e) {
            $requestError = Errors::as($e, RequestException::class);

            if ($requestError !== null) {
                $status = $requestError->getResponse()?->getStatusCode();

                return match ($status) {
                    404 => throw ExternalServiceException::userNotFound($username)->wrap($e),
                    429 => throw ExternalServiceException::rateLimitExceeded()->wrap($e),
                    default => throw ExternalServiceException::requestFailed()->wrap($e),
                };
            }

            throw $e;
        }
    }
}
```

### Job Failure Handling

```php
use Cline\Throw\Support\Errors;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessWebhook implements ShouldQueue
{
    public function handle(): void
    {
        try {
            $this->processPayload();
        } catch (Throwable $e) {
            // Log different error types with appropriate severity
            if (Errors::is($e, ValidationException::class)) {
                logger()->warning('Webhook validation failed', [
                    'exception' => $e->getMessage(),
                ]);
            } elseif (Errors::is($e, InfrastructureException::class)) {
                logger()->error('Infrastructure error processing webhook', [
                    'exception' => $e->getMessage(),
                ]);
            }

            throw $e; // Re-throw to trigger job failure
        }
    }

    public function failed(Throwable $exception): void
    {
        // Extract domain-specific error details
        $domainError = Errors::as($exception, DomainException::class);

        if ($domainError !== null) {
            // Send notification with business context
            notification()->send(new WebhookFailedNotification(
                $domainError->getMessage(),
                $domainError->getContext()
            ));
        }
    }
}
```

## Testing

```php
use Cline\Throw\Support\Errors;

test('Errors::is identifies exception type in chain', function () {
    $root = new PDOException('Connection failed');
    $wrapped = new RuntimeException('Database error', 0, $root);

    expect(Errors::is($wrapped, PDOException::class))->toBeTrue();
    expect(Errors::is($wrapped, RuntimeException::class))->toBeTrue();
    expect(Errors::is($wrapped, InvalidArgumentException::class))->toBeFalse();
});

test('Errors::as casts exception from chain', function () {
    $root = new PDOException('Connection failed');
    $wrapped = new RuntimeException('Database error', 0, $root);

    $pdo = Errors::as($wrapped, PDOException::class);

    expect($pdo)->toBeInstanceOf(PDOException::class)
        ->and($pdo->getMessage())->toBe('Connection failed');
});

test('Errors::as returns null when type not found', function () {
    $exception = new RuntimeException('Error');

    $result = Errors::as($exception, PDOException::class);

    expect($result)->toBeNull();
});
```

## When to Use

### Use Errors::is() when:
- Checking if an exception type exists anywhere in the chain
- Implementing error-specific handling logic
- Working with wrapped exceptions from third-party code

### Use Errors::as() when:
- You need to access exception-specific methods or properties
- Extracting context from domain exceptions
- Type-safe exception handling with IDE autocomplete

### Use traditional instanceof when:
- You only care about the top-level exception type
- Not dealing with wrapped exceptions
- Performance is critical (Errors methods traverse the chain)

<a id="doc-docs-error-context"></a>

Add structured debugging information to exceptions using context, tags, and metadata. This makes error tracking, logging, and monitoring significantly more effective.

## Overview

The `HasErrorContext` trait provides three methods for attaching debugging information:

- `withContext()` - Structured contextual data about the error
- `withTags()` - Categorical labels for filtering and grouping
- `withMetadata()` - Detailed technical debugging information

## Adding Context

Use `withContext()` to attach relevant data about the circumstances of the error.

### Basic Usage

```php
use App\Exceptions\PaymentFailedException;

PaymentFailedException::insufficientFunds()
    ->withContext([
        'user_id' => $user->id,
        'amount' => $amount,
        'balance' => $account->balance,
    ])
    ->throwIf($account->balance < $amount);
```

### Request Context

```php
UnauthorizedException::invalidToken()
    ->withContext([
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'session_id' => session()->getId(),
        'attempted_action' => 'access_admin_panel',
    ])
    ->throwIf(!$token->isValid());
```

### Business Context

```php
OrderCannotBeCancelledException::alreadyShipped()
    ->withContext([
        'order_id' => $order->id,
        'status' => $order->status,
        'shipped_at' => $order->shipped_at,
        'customer_id' => $order->customer_id,
    ])
    ->throwIf($order->status === 'shipped');
```

### Multiple Context Calls

Context data merges across multiple calls:

```php
$exception = DatabaseException::queryFailed()
    ->withContext(['query' => $sql])
    ->withContext(['duration_ms' => $executionTime]);

// Result: ['query' => $sql, 'duration_ms' => $executionTime]
```

Later values override earlier ones:

```php
$exception = PaymentException::failed()
    ->withContext(['status' => 'pending'])
    ->withContext(['status' => 'failed']);

// Result: ['status' => 'failed']
```

## Adding Tags

Use `withTags()` to categorize exceptions for filtering in logging and monitoring systems.

### Basic Tagging

```php
PaymentException::gatewayTimeout()
    ->withTags(['payment', 'stripe', 'critical'])
    ->throwIf($timeout);
```

### Severity Tags

```php
// Critical errors
DatabaseException::connectionLost()
    ->withTags(['critical', 'database', 'infrastructure'])
    ->throwIf($connectionLost);

// Warning-level errors
RateLimitException::approaching()
    ->withTags(['warning', 'rate-limit'])
    ->throwIf($usage > 0.9);
```

### System Tags

```php
// Tag by affected system
ApiException::timeout()
    ->withTags(['external-api', 'payment-gateway', 'stripe'])
    ->throwIf($timedOut);

// Tag by error category
ValidationException::invalidInput()
    ->withTags(['validation', 'user-input', 'form-submission'])
    ->throwIf(!$valid);
```

### Multiple Tag Calls

Tags accumulate across calls:

```php
$exception = PaymentException::failed()
    ->withTags(['payment'])
    ->withTags(['stripe', 'critical']);

// Result: ['payment', 'stripe', 'critical']
```

## Adding Metadata

Use `withMetadata()` for detailed technical debugging information that's too verbose for regular context.

### API Response Metadata

```php
try {
    $response = $client->post('/charge', $data);
} catch (RequestException $e) {
    throw PaymentException::gatewayError()
        ->wrap($e)
        ->withContext(['user_id' => $user->id, 'amount' => $amount])
        ->withTags(['payment', 'stripe'])
        ->withMetadata([
            'request_body' => $data,
            'response_status' => $e->getResponse()?->getStatusCode(),
            'response_body' => $e->getResponse()?->getBody()?->getContents(),
            'response_headers' => $e->getResponse()?->getHeaders(),
            'duration_ms' => $duration,
        ]);
}
```

### Database Query Metadata

```php
try {
    DB::statement($sql, $bindings);
} catch (QueryException $e) {
    throw DatabaseException::queryFailed()
        ->wrap($e)
        ->withContext(['table' => 'orders'])
        ->withMetadata([
            'query' => $sql,
            'bindings' => $bindings,
            'execution_time' => $time,
            'connection' => config('database.default'),
        ]);
}
```

### Multiple Metadata Calls

Metadata merges like context:

```php
$exception = ApiException::failed()
    ->withMetadata(['request' => $requestData])
    ->withMetadata(['response' => $responseData]);

// Result: ['request' => $requestData, 'response' => $responseData]
```

## Retrieving Context Data

Access attached data using getter methods:

```php
try {
    PaymentException::failed()
        ->withContext(['user_id' => 123])
        ->withTags(['payment'])
        ->withMetadata(['debug' => 'info'])
        ->throwIf(true);
} catch (PaymentException $e) {
    $context = $e->getContext();   // ['user_id' => 123]
    $tags = $e->getTags();         // ['payment']
    $metadata = $e->getMetadata(); // ['debug' => 'info']
}
```

## Using Context in Exception Handlers

### Laravel Exception Handler

```php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Cline\Throw\Concerns\HasErrorContext;

class Handler extends ExceptionHandler
{
    public function report(Throwable $exception)
    {
        if ($this->hasErrorContext($exception)) {
            // Log context data
            Log::error($exception->getMessage(), [
                'context' => $exception->getContext(),
                'tags' => $exception->getTags(),
                'metadata' => $exception->getMetadata(),
            ]);

            // Send to monitoring service
            if (in_array('critical', $exception->getTags())) {
                $this->reportCriticalError($exception);
            }
        }

        parent::report($exception);
    }

    private function hasErrorContext(Throwable $exception): bool
    {
        return method_exists($exception, 'getContext');
    }

    private function reportCriticalError(Throwable $exception): void
    {
        // Send to Sentry, Bugsnag, etc.
        app('sentry')->captureException($exception, [
            'extra' => [
                'context' => $exception->getContext(),
                'tags' => $exception->getTags(),
                'metadata' => $exception->getMetadata(),
            ],
        ]);
    }
}
```

### Filtering by Tags

```php
public function report(Throwable $exception)
{
    if (!method_exists($exception, 'getTags')) {
        return parent::report($exception);
    }

    $tags = $exception->getTags();

    // Route to different handlers based on tags
    if (in_array('payment', $tags)) {
        $this->notifyFinanceTeam($exception);
    }

    if (in_array('database', $tags)) {
        $this->notifyDatabaseTeam($exception);
    }

    if (in_array('critical', $tags)) {
        $this->sendUrgentAlert($exception);
    }

    parent::report($exception);
}
```

## Real-World Patterns

### Complete Error Context

```php
// Comprehensive error reporting
PaymentFailedException::gatewayTimeout()
    ->withContext([
        'user_id' => auth()->id(),
        'order_id' => $order->id,
        'amount' => $amount,
        'currency' => 'USD',
    ])
    ->withTags(['payment', 'stripe', 'critical', 'timeout'])
    ->withMetadata([
        'gateway_request' => $requestData,
        'gateway_response' => $responseData,
        'attempt_number' => 3,
        'total_duration_ms' => 15000,
    ])
    ->throwIf($timeout);
```

### Multi-Tenant Context

```php
TenantException::quotaExceeded()
    ->withContext([
        'tenant_id' => $tenant->id,
        'tenant_name' => $tenant->name,
        'current_usage' => $usage,
        'quota_limit' => $limit,
    ])
    ->withTags(['multi-tenant', 'quota', 'billing'])
    ->throwIf($usage >= $limit);
```

### Feature Flag Context

```php
FeatureNotAvailableException::disabled()
    ->withContext([
        'feature' => 'advanced_analytics',
        'user_id' => $user->id,
        'user_plan' => $user->plan,
    ])
    ->withTags(['feature-flag', 'access-control'])
    ->throwUnless(Feature::enabled('advanced_analytics'));
```

## Best Practices

1. **Context for what happened** - User IDs, resource IDs, relevant values
2. **Tags for categorization** - System, severity, error type
3. **Metadata for debugging** - Full request/response data, detailed technical info
4. **Sanitize sensitive data** - Don't include passwords, tokens, or PII
5. **Be consistent** - Use similar context keys across your application
6. **Tag strategically** - Use tags that help you filter in monitoring tools

## Next Steps

- Learn about [Error Wrapping](#doc-docs-error-wrapping) for exception chains
- See [Base Exceptions](#doc-docs-base-exceptions) for categorizing errors
- Explore [Assertions](#doc-docs-assertions) for the `ensure()` helper

<a id="doc-docs-error-wrapping"></a>

Wrap lower-level exceptions with domain-specific exceptions while preserving the original error for debugging. This pattern maintains clean error boundaries between application layers.

## Overview

The `WrapsErrors` trait provides the `wrap()` method to:

- Catch low-level exceptions (PDOException, RequestException, etc.)
- Re-throw as domain-specific exceptions
- Preserve the original exception in the exception chain
- Maintain all context, tags, and metadata

## Basic Usage

### Wrapping Database Exceptions

```php
use Cline\Throw\Exceptions\InfrastructureException;

final class DatabaseException extends InfrastructureException
{
    public static function queryFailed(): self
    {
        return new self('Database query failed');
    }
}

try {
    $db->query($sql);
} catch (PDOException $e) {
    throw DatabaseException::queryFailed()->wrap($e);
}
```

### Wrapping API Exceptions

```php
use Cline\Throw\Exceptions\InfrastructureException;

final class PaymentGatewayException extends InfrastructureException
{
    public static function requestFailed(): self
    {
        return new self('Payment gateway request failed');
    }
}

try {
    $response = $client->post('/charge', $data);
} catch (RequestException $e) {
    throw PaymentGatewayException::requestFailed()->wrap($e);
}
```

## Why Wrap Exceptions?

### Clean Layer Boundaries

```php
// ❌ Bad - Exposes infrastructure details to application layer
public function chargeCustomer(Customer $customer, Money $amount): void
{
    try {
        $this->stripe->charges->create([/* ... */]);
    } catch (ApiErrorException $e) {
        // Application layer now depends on Stripe SDK exception
        throw $e;
    }
}

// ✅ Good - Application layer sees domain exception
public function chargeCustomer(Customer $customer, Money $amount): void
{
    try {
        $this->stripe->charges->create([/* ... */]);
    } catch (ApiErrorException $e) {
        throw PaymentFailedException::gatewayError()->wrap($e);
    }
}
```

### Preserve Original Exception

The wrapped exception is preserved in two ways:

```php
try {
    $db->query($sql);
} catch (PDOException $e) {
    $wrapped = DatabaseException::queryFailed()->wrap($e);

    $wrapped->getWrapped();   // Returns the PDOException
    $wrapped->getPrevious();  // Also returns the PDOException
}
```

## Combining with Context

Wrap exceptions and add context in one fluent chain:

```php
try {
    $db->query($sql);
} catch (PDOException $e) {
    throw DatabaseException::queryFailed()
        ->wrap($e)
        ->withContext([
            'query' => $sql,
            'bindings' => $bindings,
        ])
        ->withTags(['database', 'critical'])
        ->withMetadata([
            'connection' => config('database.default'),
            'execution_time' => $executionTime,
        ]);
}
```

## Real-World Patterns

### Database Layer

```php
namespace App\Exceptions;

use Cline\Throw\Exceptions\InfrastructureException;

final class DatabaseException extends InfrastructureException
{
    public static function queryFailed(): self
    {
        return new self('Database query failed');
    }

    public static function connectionFailed(): self
    {
        return new self('Failed to connect to database');
    }

    public static function transactionFailed(): self
    {
        return new self('Database transaction failed');
    }
}

// Usage in repository
class OrderRepository
{
    public function save(Order $order): void
    {
        try {
            DB::table('orders')->insert($order->toArray());
        } catch (QueryException $e) {
            throw DatabaseException::queryFailed()
                ->wrap($e)
                ->withContext(['order_id' => $order->id])
                ->withTags(['database', 'orders']);
        }
    }
}
```

### HTTP Client Layer

```php
namespace App\Exceptions;

use Cline\Throw\Exceptions\InfrastructureException;

final class ExternalApiException extends InfrastructureException
{
    public static function requestFailed(string $service): self
    {
        return new self("Request to {$service} failed");
    }

    public static function timeout(string $service): self
    {
        return new self("{$service} request timed out");
    }
}

// Usage in service
class PaymentGatewayService
{
    public function charge(Money $amount, string $token): PaymentIntent
    {
        try {
            $response = $this->client->post('/charge', [
                'amount' => $amount->getAmount(),
                'token' => $token,
            ]);
        } catch (ConnectException $e) {
            throw ExternalApiException::timeout('Stripe')
                ->wrap($e)
                ->withTags(['payment', 'stripe', 'timeout']);
        } catch (RequestException $e) {
            throw ExternalApiException::requestFailed('Stripe')
                ->wrap($e)
                ->withContext(['amount' => $amount->getAmount()])
                ->withMetadata([
                    'response_status' => $e->getResponse()?->getStatusCode(),
                    'response_body' => $e->getResponse()?->getBody()?->getContents(),
                ]);
        }
    }
}
```

### File System Layer

```php
namespace App\Exceptions;

use Cline\Throw\Exceptions\InfrastructureException;

final class FileSystemException extends InfrastructureException
{
    public static function cannotRead(string $path): self
    {
        return new self("Cannot read file: {$path}");
    }

    public static function cannotWrite(string $path): self
    {
        return new self("Cannot write to file: {$path}");
    }
}

// Usage
class FileStorage
{
    public function read(string $path): string
    {
        try {
            return file_get_contents($path);
        } catch (ErrorException $e) {
            throw FileSystemException::cannotRead($path)
                ->wrap($e)
                ->withContext(['path' => $path, 'permissions' => fileperms($path)]);
        }
    }
}
```

### Cache Layer

```php
namespace App\Exceptions;

use Cline\Throw\Exceptions\InfrastructureException;

final class CacheException extends InfrastructureException
{
    public static function connectionFailed(string $driver): self
    {
        return new self("Failed to connect to {$driver} cache");
    }

    public static function operationFailed(string $operation): self
    {
        return new self("Cache {$operation} operation failed");
    }
}

// Usage
class CacheService
{
    public function remember(string $key, callable $callback, int $ttl): mixed
    {
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (RedisException $e) {
            throw CacheException::operationFailed('remember')
                ->wrap($e)
                ->withContext(['key' => $key, 'ttl' => $ttl])
                ->withTags(['cache', 'redis']);
        }
    }
}
```

## Accessing Wrapped Exceptions

### Check if Exception is Wrapped

```php
if ($exception->hasWrapped()) {
    $original = $exception->getWrapped();
    // Handle original exception
}
```

### Type Checking Wrapped Exceptions

```php
try {
    // ... some operation
} catch (DatabaseException $e) {
    if ($e->getWrapped() instanceof PDOException) {
        // Handle PDO-specific errors
        $pdoError = $e->getWrapped();
        $errorCode = $pdoError->getCode();
    }
}
```

### Full Exception Chain

```php
try {
    // ... some operation
} catch (DatabaseException $e) {
    $current = $e;

    // Walk the entire exception chain
    while ($current !== null) {
        echo $current->getMessage() . PHP_EOL;
        $current = $current->getPrevious();
    }
}
```

## Exception Handler Integration

### Laravel Exception Handler

```php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Cline\Throw\Concerns\WrapsErrors;

class Handler extends ExceptionHandler
{
    public function report(Throwable $exception)
    {
        // Log wrapped exception details
        if (method_exists($exception, 'getWrapped') && $exception->hasWrapped()) {
            $wrapped = $exception->getWrapped();

            Log::error('Wrapped exception detected', [
                'domain_exception' => get_class($exception),
                'domain_message' => $exception->getMessage(),
                'original_exception' => get_class($wrapped),
                'original_message' => $wrapped->getMessage(),
                'context' => method_exists($exception, 'getContext')
                    ? $exception->getContext()
                    : [],
            ]);
        }

        parent::report($exception);
    }
}
```

### Sentry/Bugsnag Integration

```php
public function report(Throwable $exception)
{
    if (method_exists($exception, 'getWrapped') && $exception->hasWrapped()) {
        // Report both exceptions to Sentry
        app('sentry')->captureException($exception->getWrapped(), [
            'extra' => [
                'wrapped_by' => get_class($exception),
                'context' => $exception->getContext() ?? [],
            ],
        ]);
    }

    parent::report($exception);
}
```

## Best Practices

1. **Wrap at boundaries** - Catch low-level exceptions at layer boundaries (repository, service, etc.)
2. **Add context** - Include relevant data about the operation that failed
3. **Use domain exceptions** - Wrap with exceptions that make sense in your domain
4. **Preserve original** - Always wrap rather than replacing the original exception
5. **Tag for routing** - Use tags to route wrapped exceptions to appropriate handlers
6. **Check wrapped type** - Use type checking on wrapped exceptions for specific handling

## Testing Wrapped Exceptions

```php
use Tests\Fixtures\TestInfrastructureException;

test('wraps PDOException', function () {
    $pdo = new PDOException('Connection failed');
    $wrapped = TestInfrastructureException::databaseFailure()->wrap($pdo);

    expect($wrapped->getWrapped())
        ->toBeInstanceOf(PDOException::class)
        ->and($wrapped->getWrapped()->getMessage())
        ->toBe('Connection failed')
        ->and($wrapped->hasWrapped())
        ->toBeTrue();
});
```

## Next Steps

- Learn about [Error Context](#doc-docs-error-context) for adding debugging data
- See [Base Exceptions](#doc-docs-base-exceptions) for choosing the right exception type
- Explore [Assertions](#doc-docs-assertions) for the `ensure()` helper pattern

<a id="doc-docs-exception-filtering"></a>

Search and filter through exception chains to find specific errors buried in wrapped exceptions.

## Overview

Exception filtering provides methods to traverse exception chains (via `getPrevious()`) and find specific exception types or patterns. This is essential when dealing with wrapped exceptions where the root cause is buried several layers deep.

```php
$pdoError = $exception->findFirst(PDOException::class);
if ($pdoError !== null) {
    logger()->error('Database error', [
        'code' => $pdoError->getCode(),
    ]);
}
```

## Available Methods

### findFirst()

Find the first exception of a specific type in the chain.

```php
try {
    $service->process($data);
} catch (Throwable $e) {
    $validation = $e->findFirst(ValidationException::class);
    if ($validation !== null) {
        return response()->json(['errors' => $validation->getErrors()], 422);
    }
}
```

### findAll()

Find all exceptions of a specific type in the chain.

```php
try {
    $service->processMultiple($items);
} catch (Throwable $e) {
    $validationErrors = $e->findAll(ValidationException::class);
    foreach ($validationErrors as $error) {
        logger()->warning($error->getMessage());
    }
}
```

### getChain()

Get the entire exception chain as an array.

```php
foreach ($exception->getChain() as $i => $e) {
    logger()->debug("Exception #{$i}: " . get_class($e) . " - " . $e->getMessage());
}
```

### filterChain()

Filter the exception chain using a custom callback.

```php
$networkErrors = $exception->filterChain(
    fn($e) => str_contains($e->getMessage(), 'connection')
);
```

### hasInChain()

Check if the exception chain contains a specific type.

```php
if ($exception->hasInChain(PDOException::class)) {
    // Handle database error
}
```

### getRootCause()

Get the root cause exception (the last in the chain).

```php
$root = $exception->getRootCause();
logger()->error('Root cause: ' . get_class($root) . ' - ' . $root->getMessage());
```

### getChainDepth()

Get the number of exceptions in the chain.

```php
if ($exception->getChainDepth() > 5) {
    logger()->warning('Deep exception chain detected');
}
```

## Real-World Examples

### Finding Validation Errors in Service Layer

```php
class OrderController
{
    public function create(Request $request)
    {
        try {
            $order = $this->orderService->create($request->all());
            return response()->json($order, 201);
        } catch (Throwable $e) {
            // Service may wrap validation exceptions
            $validation = $e->findFirst(ValidationException::class);

            if ($validation !== null) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors(),
                ], 422);
            }

            throw $e;
        }
    }
}
```

### Logging Entire Exception Chain

```php
class ExceptionLogger
{
    public function log(Throwable $exception): void
    {
        if (!method_exists($exception, 'getChain')) {
            logger()->error($exception->getMessage());
            return;
        }

        $chain = $exception->getChain();

        logger()->error('Exception chain detected', [
            'depth' => count($chain),
            'chain' => array_map(function($e) {
                return [
                    'type' => get_class($e),
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
            }, $chain),
        ]);
    }
}
```

### Finding Database Errors in Multi-Layer Architecture

```php
class DatabaseErrorHandler
{
    public function handle(Throwable $exception): void
    {
        if (!method_exists($exception, 'findFirst')) {
            return;
        }

        // Look for PDO exceptions anywhere in the chain
        $pdoError = $exception->findFirst(PDOException::class);

        if ($pdoError !== null) {
            logger()->critical('Database error detected', [
                'error_code' => $pdoError->getCode(),
                'sql_state' => $pdoError->errorInfo[0] ?? null,
                'driver_code' => $pdoError->errorInfo[1] ?? null,
                'message' => $pdoError->errorInfo[2] ?? $pdoError->getMessage(),
            ]);

            // Alert ops team for certain error codes
            if (in_array($pdoError->getCode(), [2002, 2003, 2006])) {
                $this->alertOpsTeam('Database connection lost', $pdoError);
            }
        }
    }
}
```

### Filtering Critical Errors for Alerting

```php
class ErrorAlerter
{
    public function shouldAlert(Throwable $exception): bool
    {
        if (!method_exists($exception, 'filterChain')) {
            return $exception->getCode() >= 500;
        }

        // Find all critical errors in the chain
        $criticalErrors = $exception->filterChain(
            fn($e) => $e->getCode() >= 500
        );

        return count($criticalErrors) > 0;
    }

    public function alert(Throwable $exception): void
    {
        if ($this->shouldAlert($exception)) {
            $criticalErrors = $exception->filterChain(
                fn($e) => $e->getCode() >= 500
            );

            foreach ($criticalErrors as $error) {
                $this->sendAlert([
                    'type' => get_class($error),
                    'message' => $error->getMessage(),
                    'code' => $error->getCode(),
                ]);
            }
        }
    }
}
```

### Finding All Database Errors

```php
class DatabaseMonitor
{
    public function reportDatabaseErrors(Throwable $exception): void
    {
        if (!method_exists($exception, 'findAll')) {
            return;
        }

        $dbErrors = $exception->findAll(PDOException::class);

        if (empty($dbErrors)) {
            return;
        }

        logger()->warning('Multiple database errors detected', [
            'count' => count($dbErrors),
            'errors' => array_map(fn($e) => [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], $dbErrors),
        ]);

        // Might indicate connection pool issues
        if (count($dbErrors) > 3) {
            $this->checkConnectionPool();
        }
    }
}
```

### Detecting Deep Exception Wrapping

```php
class ExceptionAnalyzer
{
    public function analyze(Throwable $exception): array
    {
        if (!method_exists($exception, 'getChainDepth')) {
            return ['depth' => 1];
        }

        $depth = $exception->getChainDepth();

        // Deep wrapping might indicate architectural issues
        if ($depth > 5) {
            logger()->warning('Deep exception chain detected', [
                'depth' => $depth,
                'chain' => array_map(
                    fn($e) => get_class($e),
                    $exception->getChain()
                ),
            ]);
        }

        return [
            'depth' => $depth,
            'root_cause' => get_class($exception->getRootCause()),
        ];
    }
}
```

### Custom Chain Filtering

```php
class ExceptionFilter
{
    public function findNetworkErrors(Throwable $exception): array
    {
        if (!method_exists($exception, 'filterChain')) {
            return [];
        }

        return $exception->filterChain(function($e) {
            $message = strtolower($e->getMessage());

            return str_contains($message, 'connection')
                || str_contains($message, 'timeout')
                || str_contains($message, 'network')
                || str_contains($message, 'unreachable');
        });
    }

    public function findAppErrors(Throwable $exception): array
    {
        if (!method_exists($exception, 'filterChain')) {
            return [];
        }

        return $exception->filterChain(function($e) {
            return str_starts_with(get_class($e), 'App\Exceptions\')
                && $e->getCode() > 0;
        });
    }
}
```

### Finding Specific Error Codes

```php
class PaymentErrorHandler
{
    public function handlePaymentError(Throwable $exception): void
    {
        if (!method_exists($exception, 'filterChain')) {
            return;
        }

        // Find all payment-related errors
        $paymentErrors = $exception->filterChain(
            fn($e) => $e instanceof PaymentException
        );

        foreach ($paymentErrors as $error) {
            match($error->getCode()) {
                4001 => $this->handleInsufficientFunds($error),
                4002 => $this->handleCardDeclined($error),
                4003 => $this->handleExpiredCard($error),
                default => $this->handleGenericPaymentError($error),
            };
        }
    }
}
```

### Inspecting Exception Chain for Debugging

```php
class DebugController
{
    public function showExceptionChain(Throwable $exception)
    {
        if (!method_exists($exception, 'getChain')) {
            return view('error', ['exception' => $exception]);
        }

        $chain = $exception->getChain();
        $depth = $exception->getChainDepth();
        $root = $exception->getRootCause();

        return view('debug.exception-chain', [
            'exception' => $exception,
            'chain' => $chain,
            'depth' => $depth,
            'root_cause' => $root,
            'has_pdo_errors' => $exception->hasInChain(PDOException::class),
            'has_validation_errors' => $exception->hasInChain(ValidationException::class),
        ]);
    }
}
```

### Finding Specific Layer Errors

```php
class LayeredExceptionHandler
{
    public function identifyFailureLayer(Throwable $exception): string
    {
        if (!method_exists($exception, 'findFirst')) {
            return 'unknown';
        }

        if ($exception->findFirst(RepositoryException::class) !== null) {
            return 'data_layer';
        }

        if ($exception->findFirst(ServiceException::class) !== null) {
            return 'service_layer';
        }

        if ($exception->findFirst(ValidationException::class) !== null) {
            return 'validation_layer';
        }

        if ($exception->findFirst(PDOException::class) !== null) {
            return 'database_layer';
        }

        return 'application_layer';
    }
}
```

### Conditional Error Handling Based on Chain

```php
class SmartErrorHandler
{
    public function handle(Throwable $exception): Response
    {
        if (!method_exists($exception, 'hasInChain')) {
            return $this->defaultErrorResponse($exception);
        }

        // Check for specific error types in chain
        if ($exception->hasInChain(ValidationException::class)) {
            $validation = $exception->findFirst(ValidationException::class);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validation->getErrors(),
            ], 422);
        }

        if ($exception->hasInChain(AuthenticationException::class)) {
            return response()->json([
                'message' => 'Authentication required',
            ], 401);
        }

        if ($exception->hasInChain(PDOException::class)) {
            logger()->critical('Database error in chain', [
                'root' => $exception->getRootCause()->getMessage(),
            ]);

            return response()->json([
                'message' => 'Service temporarily unavailable',
            ], 503);
        }

        return $this->defaultErrorResponse($exception);
    }
}
```

### Analyzing Exception Patterns

```php
class ExceptionMetrics
{
    public function recordMetrics(Throwable $exception): void
    {
        if (!method_exists($exception, 'getChain')) {
            $this->recordSimpleException($exception);
            return;
        }

        $chain = $exception->getChain();
        $types = array_map(fn($e) => get_class($e), $chain);

        // Record chain pattern
        $pattern = implode(' -> ', $types);

        Metrics::increment('exception.chains', [
            'pattern' => $pattern,
            'depth' => count($chain),
            'root_type' => get_class($exception->getRootCause()),
        ]);

        // Record specific error types found
        foreach ($chain as $e) {
            Metrics::increment('exception.types', [
                'type' => get_class($e),
                'code' => $e->getCode(),
            ]);
        }
    }
}
```

## Advanced Patterns

### Building Exception Reports

```php
class ExceptionReporter
{
    public function generateReport(Throwable $exception): array
    {
        if (!method_exists($exception, 'getChain')) {
            return $this->simpleReport($exception);
        }

        $chain = $exception->getChain();

        return [
            'summary' => [
                'message' => $exception->getMessage(),
                'type' => get_class($exception),
                'chain_depth' => $exception->getChainDepth(),
                'root_cause_type' => get_class($exception->getRootCause()),
            ],
            'chain' => array_map(fn($e, $i) => [
                'level' => $i,
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], $chain, array_keys($chain)),
            'analysis' => [
                'has_validation_errors' => $exception->hasInChain(ValidationException::class),
                'has_database_errors' => $exception->hasInChain(PDOException::class),
                'has_network_errors' => !empty($exception->filterChain(
                    fn($e) => str_contains($e->getMessage(), 'connection')
                )),
                'critical_errors_count' => count($exception->filterChain(
                    fn($e) => $e->getCode() >= 500
                )),
            ],
        ];
    }
}
```

### Chain-Aware Retry Logic

```php
class RetryHandler
{
    public function shouldRetry(Throwable $exception): bool
    {
        if (!method_exists($exception, 'hasInChain')) {
            return false;
        }

        // Don't retry validation errors
        if ($exception->hasInChain(ValidationException::class)) {
            return false;
        }

        // Retry connection errors
        if ($exception->hasInChain(ConnectionException::class)) {
            return true;
        }

        // Retry timeout errors
        $timeoutErrors = $exception->filterChain(
            fn($e) => str_contains($e->getMessage(), 'timeout')
        );

        return !empty($timeoutErrors);
    }
}
```

## Best Practices

1. **Check for method existence** - Use `method_exists()` before calling filtering methods on generic `Throwable`
2. **Handle null returns** - `findFirst()` returns `null` when no match is found
3. **Consider performance** - Chain traversal is O(n), cache results if checking multiple types
4. **Log chain depth** - Deep chains might indicate architectural issues
5. **Use specific types** - Filter for specific exception types rather than broad categories
6. **Combine with context** - Use filtering alongside exception context for better debugging

## See Also

- [Error Context](#doc-docs-error-context) - Structured context and metadata
- [Error Wrapping](#doc-docs-error-wrapping) - Wrapping lower-level exceptions
- [Exception Notes](#doc-docs-exception-notes) - Breadcrumb-style debugging

<a id="doc-docs-exception-groups"></a>

Handle multiple exceptions as a single unit, inspired by Python 3.11's exception groups. Perfect for validation scenarios where multiple errors can occur simultaneously.

## Overview

```php
use function Cline\Throwaise;

// Collect multiple errors
$errors = [];
if (!$email) $errors[] = new RequiredFieldException('Email required');
if (!$password) $errors[] = new RequiredFieldException('Password required');

// Throw all at once
raise($errors, 'Validation failed');
```

## Basic Usage

### Creating Exception Groups

```php
use Cline\Throw\Exceptions\ExceptionGroup;

// Direct instantiation
throw new ExceptionGroup('Validation failed', [
    new InvalidEmailException('Invalid email format'),
    new WeakPasswordException('Password too weak'),
    new RequiredFieldException('Name is required'),
]);

// Using static factory
throw ExceptionGroup::from([
    new ValidationException('Error 1'),
    new ValidationException('Error 2'),
], 'Multiple validation errors');
```

### Using the raise() Helper

```php
use function Cline\Throwaise;

$errors = [];

// Collect errors
if (!validateEmail($email)) {
    $errors[] = new InvalidEmailException();
}

if (!validatePassword($password)) {
    $errors[] = new WeakPasswordException();
}

// Raise only if errors exist
raise($errors, 'Validation failed');
```

## Real-World Examples

### Form Validation

```php
class UserRegistrationValidator
{
    public function validate(array $data): void
    {
        $errors = [];

        if (empty($data['email'])) {
            $errors[] = new RequiredFieldException('Email is required');
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = new InvalidEmailException('Invalid email format');
        }

        if (empty($data['password'])) {
            $errors[] = new RequiredFieldException('Password is required');
        } elseif (strlen($data['password']) < 8) {
            $errors[] = new WeakPasswordException('Password must be at least 8 characters');
        }

        if (empty($data['name'])) {
            $errors[] = new RequiredFieldException('Name is required');
        }

        if (!empty($data['age']) && $data['age'] < 18) {
            $errors[] = new ValidationException('Must be 18 or older');
        }

        raise($errors, 'User registration validation failed');
    }
}

// Usage in controller
try {
    $validator->validate($request->all());
    $user = User::create($request->validated());
} catch (ExceptionGroup $eg) {
    return response()->json([
        'message' => $eg->getMessage(),
        'errors' => collect($eg->getExceptions())->map(fn($e) => $e->getMessage()),
    ], 422);
}
```

### API Request Validation

```php
class ApiRequestValidator
{
    public function validateBulkCreate(array $items): void
    {
        $errors = [];

        foreach ($items as $index => $item) {
            if (empty($item['name'])) {
                $errors[] = new ValidationException("Item {$index}: name required");
            }

            if (!isset($item['price']) || $item['price'] <= 0) {
                $errors[] = new ValidationException("Item {$index}: invalid price");
            }

            if (empty($item['category'])) {
                $errors[] = new ValidationException("Item {$index}: category required");
            }
        }

        raise($errors, 'Bulk validation failed');
    }
}
```

### Database Constraints

```php
class OrderValidator
{
    public function validateBeforeCheckout(Order $order): void
    {
        $errors = [];

        if ($order->items->isEmpty()) {
            $errors[] = new ValidationException('Order must contain at least one item');
        }

        foreach ($order->items as $item) {
            if ($item->quantity > $item->product->stock) {
                $errors[] = new OutOfStockException(
                    "Product {$item->product->name} is out of stock"
                );
            }

            if (!$item->product->isAvailable()) {
                $errors[] = new ProductUnavailableException(
                    "Product {$item->product->name} is no longer available"
                );
            }
        }

        if (!$order->hasValidShippingAddress()) {
            $errors[] = new ValidationException('Invalid shipping address');
        }

        if (!$order->hasValidPaymentMethod()) {
            $errors[] = new ValidationException('Invalid payment method');
        }

        raise($errors, 'Order validation failed');
    }
}
```

## Handling Exception Groups

### Catch and Process All Errors

```php
try {
    $validator->validate($data);
} catch (ExceptionGroup $eg) {
    foreach ($eg->getExceptions() as $exception) {
        logger()->error($exception->getMessage());
    }

    return response()->json([
        'message' => 'Validation failed',
        'errors' => array_map(fn($e) => $e->getMessage(), $eg->getExceptions()),
    ], 422);
}
```

### Filter by Exception Type

```php
try {
    $processor->processItems($items);
} catch (ExceptionGroup $eg) {
    // Handle only validation errors
    $validationErrors = $eg->filter(ValidationException::class);

    foreach ($validationErrors as $error) {
        Log::warning("Validation error: {$error->getMessage()}");
    }

    // Handle system errors differently
    $systemErrors = $eg->filter(SystemException::class);

    foreach ($systemErrors as $error) {
        Log::critical("System error: {$error->getMessage()}");
        alert_team($error);
    }
}
```

### Check for Specific Error Types

```php
try {
    $service->performOperation();
} catch (ExceptionGroup $eg) {
    if ($eg->has(CriticalException::class)) {
        // Alert team about critical errors
        alert_team($eg);
    }

    if ($eg->has(ValidationException::class)) {
        // Return user-friendly validation errors
        return redirect()->back()->withErrors(
            $eg->filter(ValidationException::class)
        );
    }
}
```

### Format for Logging

```php
try {
    $batch->process();
} catch (ExceptionGroup $eg) {
    // Log formatted exception group
    logger()->error($eg->format());

    // Or custom formatting
    logger()->error('Batch processing failed', [
        'total_errors' => $eg->count(),
        'error_types' => collect($eg->getExceptions())
            ->map(fn($e) => get_class($e))
            ->unique()
            ->values()
            ->all(),
        'messages' => collect($eg->getExceptions())
            ->map(fn($e) => $e->getMessage())
            ->all(),
    ]);
}
```

## Advanced Patterns

### Nested Validation with Context

```php
class ProfileUpdateValidator
{
    public function validate(User $user, array $data): void
    {
        $errors = [];

        // Personal info validation
        $personalErrors = $this->validatePersonalInfo($data);
        foreach ($personalErrors as $error) {
            $errors[] = $error->withContext(['section' => 'personal']);
        }

        // Contact info validation
        $contactErrors = $this->validateContactInfo($data);
        foreach ($contactErrors as $error) {
            $errors[] = $error->withContext(['section' => 'contact']);
        }

        // Preferences validation
        $prefErrors = $this->validatePreferences($data);
        foreach ($prefErrors as $error) {
            $errors[] = $error->withContext(['section' => 'preferences']);
        }

        if (!empty($errors)) {
            $group = ExceptionGroup::from($errors, 'Profile validation failed');
            $group->withContext(['user_id' => $user->id]);
            $group->withTags(['validation', 'profile-update']);

            throw $group;
        }
    }
}
```

### Aggregating Async Operation Errors

```php
class BulkEmailSender
{
    public function sendToMultipleRecipients(array $recipients): void
    {
        $errors = [];

        foreach ($recipients as $recipient) {
            try {
                $this->sendEmail($recipient);
            } catch (Exception $e) {
                $errors[] = new EmailSendException(
                    "Failed to send email to {$recipient}: {$e->getMessage()}"
                );
            }
        }

        raise($errors, 'Bulk email sending encountered errors');
    }
}
```

### Conditional Error Handling

```php
try {
    $importer->import($file);
} catch (ExceptionGroup $eg) {
    // If only warnings, log and continue
    if (!$eg->has(CriticalException::class)) {
        foreach ($eg->getExceptions() as $warning) {
            Log::warning($warning->getMessage());
        }
        return;
    }

    // If critical errors exist, abort
    throw $eg;
}
```

### API Response Formatting

```php
class ExceptionGroupTransformer
{
    public function toApiResponse(ExceptionGroup $group): array
    {
        return [
            'error' => $group->getMessage(),
            'code' => 'VALIDATION_ERROR',
            'details' => collect($group->getExceptions())->map(function ($exception) {
                return [
                    'type' => class_basename($exception),
                    'message' => $exception->getMessage(),
                    'context' => method_exists($exception, 'getContext')
                        ? $exception->getContext()
                        : null,
                ];
            })->all(),
            'count' => $group->count(),
        ];
    }
}

// Usage
try {
    $validator->validate($data);
} catch (ExceptionGroup $eg) {
    return response()->json(
        $transformer->toApiResponse($eg),
        422
    );
}
```

## Best Practices

1. **Collect before raising** - Gather all errors first, then raise once
2. **Add context** - Use `withContext()` to add debugging information
3. **Filter intelligently** - Use `filter()` to handle different error types appropriately
4. **Don't raise empty groups** - The `raise()` helper automatically skips empty arrays
5. **Log formatted output** - Use `format()` for readable log entries
6. **Type-specific handling** - Use `has()` and `filter()` for targeted error handling

## Common Pitfalls

❌ **Don't raise inside loops**
```php
// Bad
foreach ($items as $item) {
    $errors = [];
    if (!valid($item)) $errors[] = new Exception();
    raise($errors); // Throws on first error
}

// Good
$errors = [];
foreach ($items as $item) {
    if (!valid($item)) $errors[] = new Exception();
}
raise($errors); // Throws once with all errors
```

❌ **Don't check isEmpty() before raise**
```php
// Bad
if (!empty($errors)) {
    raise($errors);
}

// Good
raise($errors); // Automatically handles empty arrays
```

✅ **Do add context for debugging**
```php
$group = ExceptionGroup::from($errors, 'Validation failed');
$group->withContext(['user_id' => $user->id, 'ip' => request()->ip()]);
$group->withTags(['validation', 'registration']);

throw $group;
```

## See Also

- [Assertions](#doc-docs-assertions) - Single exception throwing
- [Error Context](#doc-docs-error-context) - Adding context to exceptions
- [Basic Usage](#doc-docs-basic-usage) - Conditional exception throwing

<a id="doc-docs-exception-notes"></a>

Add breadcrumb-style annotations to exceptions for enhanced debugging context, inspired by Python's exception notes.

## Overview

Exception notes create a chronological trail of context as exceptions propagate through your application, providing valuable debugging insights without requiring exception wrapping.

```php
$exception = new PaymentException('Payment failed');

$exception->addNote('Processing order #12345')
    ->addNote('Customer ID: 789')
    ->addNote('Using Stripe gateway')
    ->addNote('Retry attempt #2');

throw $exception;
```

## Basic Usage

### Adding Single Notes

```php
$exception = new DatabaseException('Query failed');
$exception->addNote('Query: SELECT * FROM users WHERE active = 1');
$exception->addNote('Connection: primary-db');

throw $exception;
```

### Adding Multiple Notes at Once

```php
$exception = new ApiException('External API failed');

$exception->addNotes([
    'Endpoint: /api/v2/users',
    'Method: POST',
    'Timeout: 30s',
    'Retry count: 3',
]);

throw $exception;
```

### Retrieving Notes

```php
try {
    // operation that throws
} catch (Exception $e) {
    $notes = $e->getNotes();

    foreach ($notes as $note) {
        logger()->debug($note);
    }

    // Check if notes exist
    if ($e->hasNotes()) {
        // Handle notes
    }
}
```

### Formatted Output

```php
try {
    // operation
} catch (Exception $e) {
    // Get formatted notes as numbered list
    echo $e->getFormattedNotes();

    /* Output:
    Notes:
      1. Processing order #12345
      2. Using Stripe gateway
      3. Retry attempt #2
    */
}
```

## Real-World Examples

### Tracking Request Flow

```php
class OrderController
{
    public function process(Request $request, int $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            $exception = null;

            try {
                $this->validateOrder($order);
            } catch (ValidationException $e) {
                $exception = $e;
                $exception->addNote("Order ID: {$orderId}");
                $exception->addNote("User ID: {$request->user()->id}");
                $exception->addNote('Validation failed in OrderController::process');

                throw $exception;
            }

            try {
                $this->chargePayment($order);
            } catch (PaymentException $e) {
                $e->addNote("Order ID: {$orderId}");
                $e->addNote("Amount: {$order->total}");
                $e->addNote("Payment method: {$order->payment_method}");

                throw $e;
            }

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            logger()->error($e->getMessage(), [
                'notes' => $e->getNotes(),
                'exception' => get_class($e),
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
```

### Service Layer Context

```php
class UserService
{
    public function register(array $data): User
    {
        try {
            DB::beginTransaction();

            $user = User::create($data);

            try {
                $this->createProfile($user, $data);
            } catch (Exception $e) {
                $e->addNote("User ID: {$user->id}");
                $e->addNote('Failed during profile creation');

                throw $e;
            }

            try {
                $this->sendWelcomeEmail($user);
            } catch (Exception $e) {
                $e->addNote("User email: {$user->email}");
                $e->addNote('Failed during welcome email');
                $e->addNote('User created successfully');

                // Log but don't fail
                logger()->warning($e->getMessage(), [
                    'notes' => $e->getNotes(),
                ]);
            }

            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();

            $e->addNote('Transaction rolled back');

            throw $e;
        }
    }
}
```

### Background Job Tracking

```php
class ProcessOrderJob implements ShouldQueue
{
    public function handle(): void
    {
        $exception = null;

        try {
            $this->validateInventory();
        } catch (Exception $e) {
            $exception = $e;
            $exception->addNote("Job: ".self::class);
            $exception->addNote("Queue: {$this->queue}");
            $exception->addNote("Attempt: {$this->attempts()}");
            $exception->addNote('Failed at inventory validation');

            throw $exception;
        }

        try {
            $this->processPayment();
        } catch (Exception $e) {
            $e->addNote("Job: ".self::class);
            $e->addNote("Attempt: {$this->attempts()}");
            $e->addNote('Inventory validated successfully');
            $e->addNote('Failed at payment processing');

            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        $exception->addNote('Job permanently failed');

        logger()->critical($exception->getFormattedNotes());
    }
}
```

### API Integration Debugging

```php
class ExternalApiClient
{
    public function fetchUserData(int $userId): array
    {
        $exception = null;

        try {
            $response = Http::timeout(30)
                ->get("/api/users/{$userId}");

            if ($response->failed()) {
                $exception = new ApiException('API request failed');
                $exception->addNote("Endpoint: /api/users/{$userId}");
                $exception->addNote("Status code: {$response->status()}");
                $exception->addNote("Response time: {$response->handlerStats()['total_time']}s");

                throw $exception;
            }

            return $response->json();
        } catch (ConnectionException $e) {
            $e->addNote("Target: {$this->baseUrl}");
            $e->addNote("User ID: {$userId}");
            $e->addNote('Connection timeout');

            throw $e;
        } catch (RequestException $e) {
            $e->addNote("User ID: {$userId}");
            $e->addNote('Request failed');

            throw $e;
        }
    }
}
```

### Multi-Layer Exception Propagation

```php
// Repository layer
class UserRepository
{
    public function findByEmail(string $email): User
    {
        try {
            return User::where('email', $email)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $e->addNote('Repository: UserRepository');
            $e->addNote("Email: {$email}");

            throw $e;
        }
    }
}

// Service layer
class AuthenticationService
{
    public function authenticate(string $email, string $password): User
    {
        try {
            $user = $this->userRepository->findByEmail($email);
        } catch (ModelNotFoundException $e) {
            $e->addNote('Service: AuthenticationService');
            $e->addNote('Authentication attempt failed');

            throw $e;
        }

        if (!Hash::check($password, $user->password)) {
            $exception = new InvalidCredentialsException();
            $exception->addNote("User ID: {$user->id}");
            $exception->addNote('Password verification failed');

            throw $exception;
        }

        return $user;
    }
}

// Controller layer
class LoginController
{
    public function login(Request $request)
    {
        try {
            $user = $this->authService->authenticate(
                $request->email,
                $request->password
            );
        } catch (Exception $e) {
            $e->addNote('Controller: LoginController');
            $e->addNote("IP: {$request->ip()}");
            $e->addNote("User agent: {$request->userAgent()}");

            logger()->warning('Login failed', [
                'notes' => $e->getNotes(),
                // Notes will include:
                // - Repository: UserRepository
                // - Email: user@example.com
                // - Service: AuthenticationService
                // - Authentication attempt failed
                // - Controller: LoginController
                // - IP: 127.0.0.1
                // - User agent: Mozilla/5.0...
            ]);

            return redirect()->back()->with('error', 'Invalid credentials');
        }

        return redirect()->dashboard();
    }
}
```

## Advanced Patterns

### Conditional Notes

```php
try {
    $result = $this->processData($data);
} catch (Exception $e) {
    $e->addNote("Data size: ".count($data)." items");

    if (app()->environment('local')) {
        $e->addNote("Data dump: ".json_encode($data));
    }

    if ($this->retryCount > 0) {
        $e->addNote("Retry count: {$this->retryCount}");
    }

    throw $e;
}
```

### Combining with Context

```php
try {
    $payment = $this->gateway->charge($amount);
} catch (PaymentException $e) {
    $e->withContext([
        'user_id' => $user->id,
        'amount' => $amount,
        'currency' => 'USD',
    ])
        ->withTags(['payment', 'stripe'])
        ->addNote("Processing payment for order #{$order->id}")
        ->addNote("Gateway: {$this->gateway->name()}")
        ->addNote("Charge ID: {$e->getChargeId()}")
        ->withMetadata([
            'gateway_response' => $e->getGatewayResponse(),
        ]);

    throw $e;
}
```

### Formatted Logging

```php
try {
    $this->processJob();
} catch (Exception $e) {
    logger()->error($e->getMessage()."
".$e->getFormattedNotes(), [
        'exception_class' => get_class($e),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);

    /* Log output:
    [2024-01-15 10:30:45] production.ERROR: Job processing failed
    Notes:
      1. Job: ProcessOrderJob
      2. Queue: high-priority
      3. Attempt: 3 of 5
      4. Order ID: 12345
      5. Failed at payment processing
    */
}
```

### Exception Notes in Tests

```php
it('tracks execution context through exception chain', function () {
    $exception = new RuntimeException('Error');

    $exception->addNote('Step 1: Validation')
        ->addNote('Step 2: Processing')
        ->addNote('Step 3: Failed at database');

    expect($exception->getNotes())->toHaveCount(3)
        ->and($exception->hasNotes())->toBeTrue()
        ->and($exception->getFormattedNotes())->toContain('Notes:');
});
```

## Best Practices

1. **Add notes early** - Annotate exceptions as soon as you catch them
2. **Be specific** - Include relevant IDs, timestamps, and context
3. **Chronological order** - Notes maintain order, creating a breadcrumb trail
4. **Don't duplicate context** - Use `withContext()` for structured data, notes for narrative
5. **Include layer information** - Identify which layer (controller/service/repository) added the note
6. **Use for debugging** - Notes are perfect for production debugging trails

## Common Patterns

### Before/After State

```php
$exception->addNote("Before: balance = {$user->balance}");
// operation fails
$exception->addNote("After: transaction attempted = {$amount}");
```

### Environmental Context

```php
$exception->addNote("Environment: ".app()->environment());
$exception->addNote("Debug mode: ".(config('app.debug') ? 'enabled' : 'disabled'));
```

### Timing Information

```php
$start = microtime(true);
try {
    // operation
} catch (Exception $e) {
    $duration = round((microtime(true) - $start) * 1000, 2);
    $e->addNote("Execution time: {$duration}ms");

    throw $e;
}
```

## Comparison with Other Approaches

| Feature | Notes | Context | Metadata |
|---------|-------|---------|----------|
| Purpose | Chronological breadcrumbs | Structured data | Debug information |
| Format | Array of strings | Key-value pairs | Key-value pairs |
| When to use | Execution flow | Identifiers/state | Technical details |
| Best for | Debugging trail | Logging/monitoring | Deep debugging |

## See Also

- [Error Context](#doc-docs-error-context) - Structured context and metadata
- [Error Wrapping](#doc-docs-error-wrapping) - Wrapping lower-level exceptions
- [Exception Groups](#doc-docs-exception-groups) - Handling multiple exceptions

<a id="doc-docs-exception-transformation"></a>

Functionally transform exception properties using map functions, inspired by functional programming patterns.

## Overview

Exception transformation allows you to modify exception messages, context, metadata, tags, and notes using functional transformations as exceptions propagate through your application. This provides a powerful way to normalize, enrich, or sanitize exception data.

```php
$exception->mapMessage(fn($msg) => "Payment Error: {$msg}")
    ->mapContext(fn($ctx) => [...$ctx, 'timestamp' => now()])
    ->mapTags(fn($tags) => array_map('strtolower', $tags));
```

## Available Transformations

### mapMessage()

Transform the exception message.

```php
$exception = new PaymentException('charge failed');

$exception->mapMessage(fn($msg) => ucfirst($msg));
// Message becomes: "Charge failed"

$exception->mapMessage(fn($msg) => "Payment: {$msg}");
// Message becomes: "Payment: charge failed"
```

### mapContext()

Transform the context array.

```php
$exception = new ApiException('Request failed')
    ->withContext(['user_id' => 123]);

$exception->mapContext(fn($ctx) => [...$ctx, 'timestamp' => now()]);
// Context now includes timestamp

$exception->mapContext(fn($ctx) => array_diff_key($ctx, ['password' => 1]));
// Removes password from context
```

### mapMetadata()

Transform the metadata array.

```php
$exception = new DatabaseException('Query failed')
    ->withMetadata(['query' => 'SELECT * FROM users']);

$exception->mapMetadata(fn($meta) => [
    ...$meta,
    'php_version' => PHP_VERSION,
    'memory_usage' => memory_get_usage(true),
]);
```

### mapTags()

Transform the tags array.

```php
$exception = new ValidationException('Invalid input')
    ->withTags(['Payment', 'Critical']);

$exception->mapTags(fn($tags) => array_map('strtolower', $tags));
// Tags become: ['payment', 'critical']

$exception->mapTags(fn($tags) => array_values(array_unique($tags)));
// Deduplicate tags
```

### mapNotes()

Transform the notes array.

```php
$exception = new OrderException('Processing failed')
    ->addNotes(['Note 1', 'Note 2']);

$exception->mapNotes(fn($notes) => array_map(
    fn($n) => "[".now()."] {$n}",
    $notes
));
// Adds timestamps to all notes

$exception->mapNotes(fn($notes) => array_filter(
    $notes,
    fn($n) => strlen($n) > 10
));
// Filters out short notes
```

### transform()

Apply comprehensive transformation to multiple properties.

```php
$exception = new PaymentException('error')
    ->withContext(['user_id' => 123]);

$exception->transform(function($e) {
    $e->mapMessage(fn($msg) => "Critical: {$msg}")
      ->mapContext(fn($ctx) => [...$ctx, 'severity' => 'high'])
      ->addNote('Exception transformed at '.now());
});
```

## Real-World Examples

### Normalizing Error Messages

```php
class ApiClient
{
    public function request(string $endpoint): array
    {
        try {
            return $this->http->get($endpoint);
        } catch (HttpException $e) {
            // Normalize message format
            $e->mapMessage(function($msg) {
                $msg = trim($msg);
                $msg = preg_replace('/\s+/', ' ', $msg);
                return ucfirst($msg);
            });

            throw $e;
        }
    }
}
```

### Adding Layer Context

```php
class OrderRepository
{
    public function find(int $id): Order
    {
        try {
            return Order::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $e->mapMessage(fn($msg) => "[Repository] {$msg}")
              ->mapContext(fn($ctx) => [...$ctx, 'layer' => 'repository']);

            throw $e;
        }
    }
}

class OrderService
{
    public function processOrder(int $id): void
    {
        try {
            $order = $this->repository->find($id);
        } catch (ModelNotFoundException $e) {
            $e->mapMessage(fn($msg) => "[Service] {$msg}")
              ->mapContext(fn($ctx) => [...$ctx, 'service' => 'OrderService']);

            throw $e;
        }
    }
}

// Final message: "[Service] [Repository] Order not found"
```

### Sanitizing Sensitive Data

```php
class PaymentController
{
    public function charge(Request $request)
    {
        try {
            $this->gateway->charge($request->all());
        } catch (PaymentException $e) {
            $sensitive = ['card_number', 'cvv', 'password'];

            $e->mapContext(fn($ctx) => array_diff_key(
                $ctx,
                array_flip($sensitive)
            ))
              ->mapMetadata(fn($meta) => array_diff_key(
                  $meta,
                  array_flip($sensitive)
              ));

            logger()->error($e->getMessage(), [
                'context' => $e->getContext(),
                'metadata' => $e->getMetadata(),
            ]);

            throw $e;
        }
    }
}
```

### Environment-Based Transformations

```php
try {
    $this->processData($data);
} catch (Exception $e) {
    $e->transform(function($e) use ($data) {
        if (app()->environment('production')) {
            // Remove debug info in production
            $e->mapContext(fn($ctx) => array_diff_key($ctx, ['debug' => 1]))
              ->mapMetadata(fn($meta) => array_diff_key($meta, ['trace' => 1]));
        } else {
            // Add extra debug info in development
            $e->mapContext(fn($ctx) => [
                ...$ctx,
                'raw_data' => $data,
                'debug' => true,
            ])
              ->addNote('Development mode enabled');
        }
    });

    throw $e;
}
```

### Chaining Multiple Transformations

```php
$exception = new ValidationException('invalid email')
    ->withContext(['email' => 'bad-email'])
    ->withTags(['Validation', 'Critical'])
    ->addNote('Validation started');

$exception->mapMessage(fn($msg) => ucfirst($msg))
    ->mapMessage(fn($msg) => "User Input Error: {$msg}")
    ->mapContext(fn($ctx) => [...$ctx, 'timestamp' => now()])
    ->mapContext(fn($ctx) => [...$ctx, 'ip' => request()->ip()])
    ->mapTags(fn($tags) => array_map('strtolower', $tags))
    ->mapTags(fn($tags) => [...$tags, 'user-error'])
    ->mapNotes(fn($notes) => [...$notes, 'Transformation complete']);

// Final state:
// Message: "User Input Error: Invalid email"
// Context: ['email' => 'bad-email', 'timestamp' => ..., 'ip' => '127.0.0.1']
// Tags: ['validation', 'critical', 'user-error']
// Notes: ['Validation started', 'Transformation complete']
```

### Filtering Large Metadata

```php
class LogRepository
{
    public function store(array $data): void
    {
        try {
            Log::create($data);
        } catch (DatabaseException $e) {
            $e->mapMetadata(function($meta) {
                // Filter out large values
                return array_filter(
                    $meta,
                    fn($v) => strlen(json_encode($v)) < 1000
                );
            })
              ->addNote('Large metadata values removed for logging');

            logger()->error($e->getMessage(), [
                'metadata' => $e->getMetadata(),
            ]);

            throw $e;
        }
    }
}
```

### Adding Contextual Prefixes

```php
class MultiTenantRepository
{
    public function query(int $tenantId): Collection
    {
        try {
            return DB::connection("tenant_{$tenantId}")
                ->table('records')
                ->get();
        } catch (QueryException $e) {
            $e->mapMessage(fn($msg) => "[Tenant {$tenantId}] {$msg}")
              ->mapContext(fn($ctx) => [...$ctx, 'tenant_id' => $tenantId]);

            throw $e;
        }
    }
}
```

### Normalizing Tags

```php
class ExceptionHandler
{
    public function report(Throwable $exception): void
    {
        if (method_exists($exception, 'mapTags')) {
            $exception->mapTags(function($tags) {
                // Normalize to lowercase
                $tags = array_map('strtolower', $tags);

                // Deduplicate
                $tags = array_unique($tags);

                // Add environment tag
                $tags[] = app()->environment();

                return array_values($tags);
            });
        }

        parent::report($exception);
    }
}
```

### Complex Multi-Step Transformation

```php
class OrderProcessor
{
    public function process(Order $order): void
    {
        try {
            $this->validateOrder($order);
            $this->chargePayment($order);
            $this->fulfillOrder($order);
        } catch (Exception $e) {
            $e->transform(function($e) use ($order) {
                // Step 1: Normalize message
                $e->mapMessage(function($msg) {
                    $msg = trim($msg);
                    $msg = preg_replace('/\s+/', ' ', $msg);
                    return ucfirst(rtrim($msg, '.'));
                });

                // Step 2: Enrich context
                $e->mapContext(fn($ctx) => [
                    ...$ctx,
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'total' => $order->total,
                    'status' => $order->status,
                    'timestamp' => now()->toIso8601String(),
                ]);

                // Step 3: Sanitize sensitive data
                $sensitive = ['password', 'card_number', 'cvv', 'ssn'];
                $e->mapContext(fn($ctx) => array_diff_key(
                    $ctx,
                    array_flip($sensitive)
                ));

                // Step 4: Normalize tags
                $e->mapTags(fn($tags) => array_values(
                    array_unique(
                        array_map('strtolower', $tags)
                    )
                ));

                // Step 5: Add processing notes
                $e->addNote("Processed in OrderProcessor at ".now());
                $e->addNote("Order status: {$order->status}");
            });

            throw $e;
        }
    }
}
```

### Conditional Transformations Based on Exception Type

```php
class ExceptionTransformer
{
    public function transform(Throwable $exception): void
    {
        if (!method_exists($exception, 'transform')) {
            return;
        }

        $exception->transform(function($e) {
            // Add timestamp to all exceptions
            $e->mapContext(fn($ctx) => [...$ctx, 'timestamp' => now()]);

            // Type-specific transformations
            if ($e instanceof ValidationException) {
                $e->mapMessage(fn($msg) => "Validation Error: {$msg}")
                  ->mapTags(fn($tags) => [...$tags, 'user-error']);
            }

            if ($e instanceof DatabaseException) {
                $e->mapMessage(fn($msg) => "Database Error: {$msg}")
                  ->mapTags(fn($tags) => [...$tags, 'infrastructure']);
            }

            if ($e instanceof PaymentException) {
                $e->mapMessage(fn($msg) => "Payment Error: {$msg}")
                  ->mapTags(fn($tags) => [...$tags, 'critical', 'payment']);
            }
        });
    }
}
```

## Advanced Patterns

### Middleware-Style Transformations

```php
class ExceptionPipeline
{
    protected array $transformers = [];

    public function pipe(callable $transformer): self
    {
        $this->transformers[] = $transformer;
        return $this;
    }

    public function process(Throwable $exception): void
    {
        if (!method_exists($exception, 'transform')) {
            return;
        }

        foreach ($this->transformers as $transformer) {
            $exception->transform($transformer);
        }
    }
}

// Usage
$pipeline = new ExceptionPipeline();

$pipeline->pipe(fn($e) => $e->mapMessage(fn($m) => ucfirst($m)))
    ->pipe(fn($e) => $e->mapContext(fn($c) => [...$c, 'timestamp' => now()]))
    ->pipe(fn($e) => $e->mapTags(fn($t) => array_map('strtolower', $t)))
    ->process($exception);
```

### Transformation Based on Context

```php
try {
    $this->performOperation($data);
} catch (Exception $e) {
    $e->transform(function($e) use ($data) {
        // Transform based on data size
        if (count($data) > 1000) {
            $e->addNote('Large dataset: '.count($data).' items')
              ->mapContext(fn($ctx) => [...$ctx, 'dataset_size' => 'large']);
        }

        // Transform based on user role
        if (auth()->user()->isAdmin()) {
            $e->mapContext(fn($ctx) => [...$ctx, 'admin_view' => true]);
        } else {
            // Remove sensitive data for non-admins
            $e->mapMetadata(fn($meta) => array_filter(
                $meta,
                fn($k) => !in_array($k, ['query', 'trace']),
                ARRAY_FILTER_USE_KEY
            ));
        }
    });

    throw $e;
}
```

## Best Practices

1. **Transform early** - Apply transformations as soon as you catch exceptions
2. **Chain related transformations** - Group related modifications together
3. **Be consistent** - Use the same transformation patterns across your application
4. **Don't lose information** - Add, don't replace critical context
5. **Sanitize sensitive data** - Always remove passwords, keys, tokens
6. **Use `transform()` for complex changes** - Multiple properties at once
7. **Keep transformations pure** - Don't cause side effects in transformation functions

## Comparison with Other Approaches

| Approach | Use Case | Example |
|----------|----------|---------|
| `mapMessage()` | Single message change | Prefix, normalize format |
| `mapContext()` | Add/remove context data | Timestamps, sanitization |
| `mapMetadata()` | Debug information | Environment info, metrics |
| `mapTags()` | Categorization | Normalize, deduplicate |
| `mapNotes()` | Breadcrumb trail | Add timestamps, filter |
| `transform()` | Multiple properties | Complex multi-step changes |

## See Also

- [Error Context](#doc-docs-error-context) - Structured context and metadata
- [Exception Notes](#doc-docs-exception-notes) - Breadcrumb-style debugging
- [Error Wrapping](#doc-docs-error-wrapping) - Wrapping lower-level exceptions

<a id="doc-docs-result-integration"></a>

Convert between `Attempt` (Try monad) and `Result` type for explicit error handling without exceptions.

## Overview

The `toResult()` method bridges the gap between exception-based and value-based error handling, allowing you to work with Rust-inspired Result types.

```php
use function Cline\Throwttempt;

// Convert Attempt to Result
$result = attempt(fn() => User::findOrFail($id))->toResult();
// Returns Ok<User> or Err<Throwable>
```

## Basic Usage

### Success Case (Ok)

```php
use function Cline\Throwttempt;

$result = attempt(fn() => 42)->toResult();

// Check if Ok
if ($result->isOk()) {
    echo $result->unwrap(); // 42
}
```

### Failure Case (Err)

```php
use function Cline\Throwttempt;

$result = attempt(fn() => throw new RuntimeException('Error'))
    ->toResult();

// Check if Err
if ($result->isErr()) {
    $exception = $result->unwrapErr();
    echo $exception->getMessage(); // "Error"
}
```

## Chaining with Result Methods

### Transform Success Values

```php
$name = attempt(fn() => User::find($id))
    ->toResult()
    ->map(fn($user) => $user->name)
    ->unwrapOr('Guest');
```

### Transform Errors

```php
$result = attempt(fn() => processPayment())
    ->toResult()
    ->mapErr(fn($e) => "Payment failed: {$e->getMessage()}");

if ($result->isErr()) {
    logger()->error($result->unwrapErr());
}
```

### Provide Defaults

```php
$config = attempt(fn() => loadConfig())
    ->toResult()
    ->unwrapOr(['debug' => false]);
```

## Real-World Examples

### API Request Handling

```php
class ApiClient
{
    public function fetchUser(int $id): Result
    {
        return attempt(function () use ($id) {
            $response = Http::get("/users/{$id}");

            if ($response->failed()) {
                throw new ApiException('Request failed');
            }

            return $response->json();
        })->toResult();
    }
}

// Usage
$result = $apiClient->fetchUser(123);

$user = $result
    ->map(fn($data) => new User($data))
    ->mapErr(fn($e) => logger()->error($e))
    ->unwrapOr(null);
```

### Database Operations

```php
class UserRepository
{
    public function findByEmail(string $email): Result
    {
        return attempt(fn() => User::where('email', $email)
            ->firstOrFail()
        )->toResult();
    }
}

// Usage
$repository->findByEmail('user@example.com')
    ->map(fn($user) => $user->notify(new WelcomeNotification()))
    ->mapErr(fn($e) => Log::info("User not found: {$e->getMessage()}"));
```

### File Operations

```php
function readConfigFile(string $path): Result
{
    return attempt(function () use ($path) {
        if (!file_exists($path)) {
            throw new FileException("File not found: {$path}");
        }

        $contents = file_get_contents($path);

        return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    })->toResult();
}

// Usage with chaining
$config = readConfigFile('config.json')
    ->map(fn($data) => new Config($data))
    ->unwrapOrElse(fn($e) => Config::defaults());
```

### Service Layer

```php
class PaymentService
{
    public function charge(Order $order): Result
    {
        return attempt(function () use ($order) {
            $this->validateOrder($order);

            $payment = $this->gateway->charge(
                $order->total,
                $order->paymentMethod
            );

            $order->markAsPaid($payment);

            return $payment;
        })->toResult();
    }
}

// Usage in controller
public function processOrder(Request $request, int $orderId)
{
    $result = $this->paymentService
        ->charge(Order::find($orderId))
        ->toResult();

    return $result->match(
        ok: fn($payment) => response()->json([
            'success' => true,
            'payment_id' => $payment->id,
        ]),
        err: fn($e) => response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 422)
    );
}
```

## Converting to Option

You can convert Result back to Option if needed:

```php
$maybeUser = attempt(fn() => User::find($id))
    ->toResult()
    ->ok(); // Some<User> or None

$userName = $maybeUser
    ->map(fn($user) => $user->name)
    ->unwrapOr('Anonymous');
```

## Error Handling Patterns

### Explicit Error Handling

```php
$result = attempt(fn() => dangerousOperation())->toResult();

if ($result->isErr()) {
    $error = $result->unwrapErr();

    match (true) {
        $error instanceof ValidationException => handleValidation($error),
        $error instanceof DatabaseException => handleDatabase($error),
        default => handleUnknown($error),
    };
}
```

### Railway-Oriented Programming

```php
$result = attempt(fn() => validateInput($input))
    ->toResult()
    ->andThen(fn($validated) => attempt(fn() => processData($validated))->toResult())
    ->andThen(fn($processed) => attempt(fn() => saveToDatabase($processed))->toResult())
    ->map(fn($saved) => new SuccessResponse($saved))
    ->unwrapOrElse(fn($e) => new ErrorResponse($e));
```

### Collect Multiple Results

```php
$results = collect([1, 2, 3])
    ->map(fn($id) => attempt(fn() => User::findOrFail($id))->toResult());

$failures = $results->filter(fn($r) => $r->isErr());
$successes = $results->filter(fn($r) => $r->isOk());

if ($failures->isNotEmpty()) {
    logger()->warning("Failed to load users", [
        'count' => $failures->count(),
        'errors' => $failures->map(fn($r) => $r->unwrapErr()->getMessage()),
    ]);
}
```

## Best Practices

1. **Use Result for explicit error handling** - When callers need to handle errors explicitly
2. **Use Attempt for try-catch replacement** - When you want exception handling without try-catch
3. **Chain transformations** - Leverage `map()` and `mapErr()` for clean pipelines
4. **Avoid unwrap() without checks** - Always use `unwrapOr()` or check `isOk()` first
5. **Log errors in mapErr** - Use `mapErr()` for side effects like logging

## See Also

- [Attempt Monad](#doc-docs-attempt-monad) - Try monad for exception handling
- [Error Wrapping](#doc-docs-error-wrapping) - Wrapping lower-level exceptions
- [Integration Patterns](#doc-docs-integration-patterns) - Using with Laravel
