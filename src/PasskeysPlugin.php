<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys;

use Filament\Auth\Pages\EditProfile;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\View\View;
use Livewire\Livewire;
use MarcelWeidum\Passkeys\Livewire\Passkeys as LivewirePasskeys;

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
        Livewire::component('filament-passkeys', LivewirePasskeys::class);
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): View => view('filament-passkeys::login'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIMPLE_PAGE_END,
            fn (): View => view('filament-passkeys::profile'),
            scopes: $panel->getProfilePage() ?? EditProfile::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_END,
            fn (): View => view('filament-passkeys::profile'),
            scopes: $panel->getProfilePage() ?? EditProfile::class,
        );
    }
}
