<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Support;

/**
 * HTTP status code enumeration.
 *
 * Provides type-safe HTTP status codes for use with abort methods.
 * Prevents invalid status codes and improves IDE autocomplete support.
 *
 * @example Using with ensure
 * ```php
 * ensure($user !== null)->orAbort(HttpStatusCode::NotFound);
 * ```
 * @example Using with assertion helpers
 * ```php
 * ensure($user->isAdmin())->orAbort(HttpStatusCode::Forbidden);
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
enum HttpStatusCode: int
{
    // 1×× Informational
    case Continue = 100;

    // Continue
    case SwitchingProtocols = 101;

    // Switching Protocols
    case Processing = 102;

    // Processing
    case EarlyHints = 103; // Early Hints

    // 2×× Success
    case Ok = 200;

    // OK
    case Created = 201;

    // Created
    case Accepted = 202;

    // Accepted
    case NonAuthoritativeInformation = 203;

    // Non-authoritative Information
    case NoContent = 204;

    // No Content
    case ResetContent = 205;

    // Reset Content
    case PartialContent = 206;

    // Partial Content
    case MultiStatus = 207;

    // Multi-Status
    case AlreadyReported = 208;

    // Already Reported
    case ImUsed = 226; // IM Used

    // 3×× Redirection
    case MultipleChoices = 300;

    // Multiple Choices
    case MovedPermanently = 301;

    // Moved Permanently
    case Found = 302;

    // Found
    case SeeOther = 303;

    // See Other
    case NotModified = 304;

    // Not Modified
    case UseProxy = 305;

    // Use Proxy
    case TemporaryRedirect = 307;

    // Temporary Redirect
    case PermanentRedirect = 308; // Permanent Redirect

    // 4×× Client Error
    case BadRequest = 400;

    // Bad Request
    case Unauthorized = 401;

    // Unauthorized
    case PaymentRequired = 402;

    // Payment Required
    case Forbidden = 403;

    // Forbidden
    case NotFound = 404;

    // Not Found
    case MethodNotAllowed = 405;

    // Method Not Allowed
    case NotAcceptable = 406;

    // Not Acceptable
    case ProxyAuthenticationRequired = 407;

    // Proxy Authentication Required
    case RequestTimeout = 408;

    // Request Timeout
    case Conflict = 409;

    // Conflict
    case Gone = 410;

    // Gone
    case LengthRequired = 411;

    // Length Required
    case PreconditionFailed = 412;

    // Precondition Failed
    case PayloadTooLarge = 413;

    // Payload Too Large
    case RequestUriTooLong = 414;

    // Request-URI Too Long
    case UnsupportedMediaType = 415;

    // Unsupported Media Type
    case RequestedRangeNotSatisfiable = 416;

    // Requested Range Not Satisfiable
    case ExpectationFailed = 417;

    // Expectation Failed
    case ImATeapot = 418;

    // I'm a teapot
    case MisdirectedRequest = 421;

    // Misdirected Request
    case UnprocessableEntity = 422;

    // Unprocessable Entity
    case Locked = 423;

    // Locked
    case FailedDependency = 424;

    // Failed Dependency
    case TooEarly = 425;

    // Too Early
    case UpgradeRequired = 426;

    // Upgrade Required
    case PreconditionRequired = 428;

    // Precondition Required
    case TooManyRequests = 429;

    // Too Many Requests
    case RequestHeaderFieldsTooLarge = 431;

    // Request Header Fields Too Large
    case ConnectionClosedWithoutResponse = 444;

    // Connection Closed Without Response
    case UnavailableForLegalReasons = 451;

    // Unavailable For Legal Reasons
    case ClientClosedRequest = 499; // Client Closed Request

    // 5×× Server Error
    case InternalServerError = 500;

    // Internal Server Error
    case NotImplemented = 501;

    // Not Implemented
    case BadGateway = 502;

    // Bad Gateway
    case ServiceUnavailable = 503;

    // Service Unavailable
    case GatewayTimeout = 504;

    // Gateway Timeout
    case HttpVersionNotSupported = 505;

    // HTTP Version Not Supported
    case VariantAlsoNegotiates = 506;

    // Variant Also Negotiates
    case InsufficientStorage = 507;

    // Insufficient Storage
    case LoopDetected = 508;

    // Loop Detected
    case NotExtended = 510;

    // Not Extended
    case NetworkAuthenticationRequired = 511;

    // Network Authentication Required
    case NetworkConnectTimeoutError = 599; // Network Connect Timeout Error
}
