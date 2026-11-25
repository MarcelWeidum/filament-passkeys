@include('filament-passkeys::assets')

<div x-data="{ loginUrl: @js(filament()->getCurrentOrDefaultPanel()->getUrl()) }">
    <x-filament::button
        type="button"
        icon="heroicon-o-key"
        color="gray"
        class="w-full"
        x-on:click="window.FilamentPasskeys.login(loginUrl)"
    >
        {{ __('filament-passkeys::passkeys.authenticate_using_passkey') }}
    </x-filament::button>
</div>
