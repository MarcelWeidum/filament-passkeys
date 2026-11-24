{{-- Loaded on request (see the service provider): only the pages that show a passkey button or form load them. Filament's renderer keeps the CSP nonce and `data-navigate-track`. --}}
@assets
    @foreach (\Filament\Support\Facades\FilamentAsset::getStyles(['marcelweidum/filament-passkeys']) as $asset)
        {{ $asset->getHtml() }}
    @endforeach
    @foreach (\Filament\Support\Facades\FilamentAsset::getScripts(['marcelweidum/filament-passkeys']) as $asset)
        {{ $asset->getHtml() }}
    @endforeach
@endassets
