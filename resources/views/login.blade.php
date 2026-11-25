<div
    x-data="{
        loginUrl: @js(filament()->getCurrentOrDefaultPanel()->getUrl()),
        remember: @js($remember),
    }"
>
    <x-filament::button
        type="button"
        icon="heroicon-o-key"
        color="gray"
        class="w-full"
        x-on:click="window.FilamentPasskeys.login(loginUrl, () => remember ?? Boolean($wire.$get('data.remember')))"
    >
        {{ __('filament-passkeys::passkeys.authenticate_using_passkey') }}
    </x-filament::button>
</div>
