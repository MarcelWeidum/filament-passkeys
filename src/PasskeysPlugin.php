<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys;

use Closure;
use Filament\Auth\Pages\EditProfile;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\View\View;

final class PasskeysPlugin implements Plugin
{
    use EvaluatesClosures;

    protected bool|Closure|null $remember = null;

    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(self::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-passkeys';
    }

    /**
     * Remember users who sign in with a passkey. By default, the login
     * form's "Remember me" checkbox decides.
     */
    public function remember(bool|Closure|null $condition = true): static
    {
        $this->remember = $condition;

        return $this;
    }

    public function getRemember(): ?bool
    {
        return $this->evaluate($this->remember);
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): View => view('filament-passkeys::login', [
                'remember' => $this->getRemember(),
            ]),
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
