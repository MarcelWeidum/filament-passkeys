<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class PasskeysPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-passkeys';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
