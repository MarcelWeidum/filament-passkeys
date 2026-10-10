# Filament Passkeys

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marcelweidum/filament-passkeys.svg?style=flat-square)](https://packagist.org/packages/marcelweidum/filament-passkeys)
[![Total Downloads](https://img.shields.io/packagist/dt/marcelweidum/filament-passkeys.svg?style=flat-square)](https://packagist.org/packages/marcelweidum/filament-passkeys)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/marcelweidum/filament-passkeys/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/marcelweidum/filament-passkeys/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
![Filament 4.x](https://img.shields.io/badge/Filament-4.x-007ec6?style=flat-square)
![Filament 5.x](https://img.shields.io/badge/Filament-5.x-44cc11?style=flat-square)

Use passkeys in your filament app.
The current `4.x` version uses Laravel's native passkeys package.

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="art/cover-dark.png">
  <source media="(prefers-color-scheme: light)" srcset="art/cover-light.png">
  <img alt="Filament Passkeys cover" src="art/cover-light.png">
</picture>

&nbsp;

## Version compatibility

| Package version | Filament version | Passkeys backend | Use this when |
| --- | --- | --- | --- |
| `4.x` | Filament v5 | Laravel native passkeys (`laravel/passkeys`) | Starting a new Filament v5 app or upgrading to Laravel native passkeys |
| [`3.x`](https://github.com/MarcelWeidum/filament-passkeys/tree/3.x) | Filament v5 | Spatie passkeys (`spatie/laravel-passkeys`) | Staying on the older Spatie-backed implementation |
| [`2.x`](https://github.com/MarcelWeidum/filament-passkeys/tree/2.x) | Filament v3 or v4 | Spatie passkeys (`spatie/laravel-passkeys`) | Using Filament v3 or v4 |

## Installation

1. Install the package via composer:

```bash
composer require marcelweidum/filament-passkeys
```

2. Add Laravel's passkey interface and trait to your user model

```php
namespace App\Models;

use Laravel\Passkeys\Contracts\PasskeyUser;
use Laravel\Passkeys\PasskeyAuthenticatable;
// ...

class User extends Authenticatable implements PasskeyUser
{
    use HasFactory, Notifiable, PasskeyAuthenticatable;

    // ... 
}
```

3. Publish and run the migrations

```bash
php artisan vendor:publish --tag="passkeys-migrations"
php artisan migrate
```

Laravel registers the passkey routes automatically. You can optionally publish Laravel's passkeys config:

```bash
php artisan vendor:publish --tag="passkeys-config"
```

4. Add passkeys plugin to your Filament Panel

Add passkeys to a panel by adding the class to your Filament Panel's plugin() or plugins([]) method.

```php
use MarcelWeidum\Passkeys\PasskeysPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            PasskeysPlugin::make(),
        ])
}
```

Don't forget to add `->profile()` to you panel as well to manage your passkeys.

Creating or deleting a passkey asks for the user's current password when Laravel's `password.confirm` management middleware is enabled (the default in `laravel/passkeys` 0.2+). After a successful confirmation, Laravel remembers it for `auth.password_timeout` seconds (3 hours by default). Set `management_middleware` to `[]` in the published passkeys config if you want to disable that step.

## Upgrading from the Spatie passkeys package

This package no longer uses `spatie/laravel-passkeys`. If you are upgrading an existing 3.x application to the native Laravel passkeys version, follow the [3.x to native Laravel upgrade guide](UPGRADE.md).

For existing Spatie-backed applications, do not run Laravel's fresh `create_passkeys_table` migration against the existing table. The upgrade guide uses this package's conversion migration instead.

(Optional) If you want to customize the translations, you can publish the translations by running:

```bash
php artisan vendor:publish --tag="filament-passkeys-translations"
```

## Common problems
If you're having problems creating passkeys on your profile page, check if your `APP_URL` in the `.env` file is set to the correct url of the application.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [MarcelWeidum](https://github.com/MarcelWeidum)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
