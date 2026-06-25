<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\View\View;
use Spatie\LaravelPasskeys\Livewire\PasskeysComponent;

final class Passkeys extends PasskeysComponent implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('passkeys::passkeys.delete'))
            ->color('danger')
            ->requiresConfirmation()
            ->action(fn (array $arguments) => $this->deletePasskey($arguments['passkey']));
    }

    public function deletePasskey(int|string $passkeyId): void
    {
        parent::deletePasskey($passkeyId);

        Notification::make()
            ->title(__('filament-passkeys::passkeys.deleted_notification_title'))
            ->success()
            ->send();
    }

    public function storePasskey(string $passkey): void
    {
        parent::storePasskey($passkey);

        Notification::make()
            ->title(__('filament-passkeys::passkeys.created_notification_title'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('filament-passkeys::livewire.passkeys', data: [
            'passkeys' => $this->currentUser()->passkeys,
        ]);
    }
}
