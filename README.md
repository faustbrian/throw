[![GitHub Workflow Status][ico-tests]][link-tests]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

------

Fluent conditional exception throwing for Laravel. Provides a readable, chainable API for throwing exceptions and aborting requests based on conditions.

## Requirements

> **Requires [PHP 8.5+](https://php.net/releases/)**

## Installation

```bash
composer require cline/throw
```

## Documentation

- **[Getting Started](cookbook/getting-started.md)** - Installation and basic usage
- **[Basic Usage](cookbook/basic-usage.md)** - Throwing exceptions conditionally
- **[HTTP Responses](cookbook/http-responses.md)** - Aborting requests with status codes
- **[Integration Patterns](cookbook/integration-patterns.md)** - Using with Laravel exceptions

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please use the [GitHub security reporting form][link-security] rather than the issue queue.

## Credits

- [Brian Faust][link-maintainer]
- [All Contributors][link-contributors]

## License

The MIT License. Please see [License File](LICENSE.md) for more information.

[ico-tests]: https://github.com/faustbrian/throw/actions/workflows/quality-assurance.yaml/badge.svg
[ico-version]: https://img.shields.io/packagist/v/cline/throw.svg
[ico-license]: https://img.shields.io/badge/License-MIT-green.svg
[ico-downloads]: https://img.shields.io/packagist/dt/cline/throw.svg

[link-tests]: https://github.com/faustbrian/throw/actions
[link-packagist]: https://packagist.org/packages/cline/throw
[link-downloads]: https://packagist.org/packages/cline/throw
[link-security]: https://github.com/faustbrian/throw/security
[link-maintainer]: https://github.com/faustbrian
[link-contributors]: ../../contributors
