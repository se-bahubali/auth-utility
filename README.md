# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stallionexpress/authutility.svg?style=flat-square)](https://packagist.org/packages/stallionexpress/authutility)
[![Total Downloads](https://img.shields.io/packagist/dt/stallionexpress/authutility.svg?style=flat-square)](https://packagist.org/packages/stallionexpress/authutility)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require stallionexpress/authutility
```

## .env setup

Setup these keys in your .env file

```bash
AUTH_SERVER_URL="http://auth.stallionexpress.xyz"
CLIENT_ID="your client id"
CLIENT_SECRET="your client secret"
FRONT_END_URL="your front end redirect url"
```

## Register provider

First add Register _StallionExpress\AuthUtility\Providers\StallionServiceProvider_ in your config/app.php

```bash
'providers' => [
        StallionExpress\AuthUtility\Providers\StallionServiceProvider::class,
]
```

## Register guard

Add the below code in your config/auth.php

```bash
'guards' => [
        'token' => [
            'driver' => 'access_token',
        ],
    ],
```

## Middleware Usage

If you want to use our middleware to decode has value then add middleware to laravel project

step 1: add middleware to kernal

```bash
use StallionExpress\AuthUtility\Middleware\ReplaceRouteAndRequestHashValueMiddleware;
```

step 2: add middleware to kernal

```bash
protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            ReplaceRouteAndRequestHashValueMiddleware::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            ReplaceRouteAndRequestHashValueMiddleware::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
```

#

## Trait Usage

If you want to use PrimaryIdEncodeTrait

-   Step 1: Import namespace

```bash
use StallionExpress\AuthUtility\Trait\PrimaryIdEncodeTrait;
```

-   Step 2: Add to use

```bash
use PrimaryIdEncodeTrait;
```

If you want to use STEncodeDecodeTrait

-   Step 1: Import namespace

```bash
use StallionExpress\AuthUtility\Trait\STEncodeDecodeTrait;
```

-   Step 2: Add to use

```bash
use STEncodeDecodeTrait;
```

#

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email avinashkant@stallionexpress.ca instead of using the issue tracker.

## Credits

-   [Avinash Kant](https://github.com/se-bahubali)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

-   lluminate/support": "^10.12.0"
