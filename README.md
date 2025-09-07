# Filament Passkeys

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marcelweidum/filament-passkeys.svg?style=flat-square)](https://packagist.org/packages/marcelweidum/filament-passkeys)
[![Total Downloads](https://img.shields.io/packagist/dt/marcelweidum/filament-passkeys.svg?style=flat-square)](https://packagist.org/packages/marcelweidum/filament-passkeys)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/marcelweidum/filament-passkeys/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/marcelweidum/filament-passkeys/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
![Filament 4.x](https://img.shields.io/badge/Filament-4.x-007ec6?style=flat-square)

Use passkeys in your filament app.
This package is using the [passkeys package from spatie](https://spatie.be/docs/laravel-passkeys).

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="art/cover-dark.png">
  <source media="(prefers-color-scheme: light)" srcset="art/cover-light.png">
  <img alt="Filament Passkeys cover" src="art/cover-light.png">
</picture>

&nbsp;

## Installation

1. Install the package via composer:

```bash
composer require marcelweidum/filament-passkeys
```

2. Add the package's interface and trait to your user model

```php
namespace App\Models;

use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;
// ...

class User extends Authenticatable implements HasPasskeys
{
    use HasFactory, Notifiable, InteractsWithPasskeys;

    // ... 
}
```

3. Publish and run the migrations

```bash
php artisan vendor:publish --tag="passkeys-migrations"
php artisan migrate
```

4. Add the package provided routes

```php
// routes/web.php
Route::passkeys();
```

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
- [Spatie](https://github.com/spatie)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
