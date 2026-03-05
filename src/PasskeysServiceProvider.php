<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use MarcelWeidum\Passkeys\Livewire\Passkeys as LivewirePasskeys;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class PasskeysServiceProvider extends PackageServiceProvider
{
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
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        Livewire::component('filament-passkeys', LivewirePasskeys::class);
    }

    protected function getAssetPackageName(): string
    {
        return 'marcelweidum/filament-passkeys';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('filament-passkeys-styles', __DIR__.'/../resources/dist/filament-passkeys.css'),
            Js::make('filament-passkeys-scripts', __DIR__.'/../resources/dist/filament-passkeys.js'),
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
