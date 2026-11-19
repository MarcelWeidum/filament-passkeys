{{-- Loaded on request (see the service provider): only the pages that show a passkey button or form load them. --}}
@assets
    <link rel="stylesheet" href="{{ \Filament\Support\Facades\FilamentAsset::getStyleHref('filament-passkeys-styles', 'marcelweidum/filament-passkeys') }}">
    <script src="{{ \Filament\Support\Facades\FilamentAsset::getScriptSrc('filament-passkeys-scripts', 'marcelweidum/filament-passkeys') }}"></script>
@endassets
