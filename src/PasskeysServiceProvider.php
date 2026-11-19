<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use MarcelWeidum\Passkeys\Livewire\Passkeys as LivewirePasskey;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class PasskeysServiceProvider extends PackageServiceProvider
{
    private const MIGRATIONS_TAG = 'filament-passkeys-migrations';

    private const UPGRADE_MIGRATION = '2026_05_09_000000_migrate_spatie_passkeys_to_laravel_passkeys.php';

    public static string $name = 'filament-passkeys';

    public static string $viewNamespace = 'filament-passkeys';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(self::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('marcelweidum/filament-passkeys');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(self::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/'.self::UPGRADE_MIGRATION => database_path('migrations/'.self::UPGRADE_MIGRATION),
            ], self::MIGRATIONS_TAG);
        }

        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        Livewire::component('filament-passkeys', LivewirePasskey::class);
    }

    protected function getAssetPackageName(): string
    {
        return 'marcelweidum/filament-passkeys';
    }

    /**
     * Both are loaded on request: Filament prints every other registered asset on every page of every panel,
     * including the panels that do not use this plugin. The views that show a passkey button or form load them
     * with `@assets` (`resources/views/assets.blade.php`).
     *
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('filament-passkeys-styles', __DIR__.'/../resources/dist/filament-passkeys.css')->loadedOnRequest(),
            Js::make('filament-passkeys-scripts', __DIR__.'/../resources/dist/filament-passkeys.js')->loadedOnRequest(),
        ];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [
            //
        ];
    }
}
