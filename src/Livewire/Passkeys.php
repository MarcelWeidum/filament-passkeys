<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;
use Laravel\Passkeys\Actions\DeletePasskey;
use Laravel\Passkeys\Contracts\PasskeyUser;
use Laravel\Passkeys\Passkey;
use Laravel\Passkeys\Passkeys as LaravelPasskeys;
use Livewire\Component;
use RuntimeException;

final class Passkeys extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public string $name = '';

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('filament-passkeys::passkeys.delete'))
            ->color('danger')
            ->requiresConfirmation()
            ->schema(fn (): array => $this->needsPasswordConfirmation() ? $this->passwordConfirmationSchema() : [])
            ->action(function (array $arguments, array $data): void {
                if ($this->needsPasswordConfirmation() && blank($data['password'] ?? null)) {
                    abort(403);
                }

                if ($this->usesPasswordConfirmationMiddleware()) {
                    session()->passwordConfirmed();
                }

                $this->deletePasskey($arguments['passkey']);
            });
    }

    public function confirmPasswordAction(): Action
    {
        return Action::make('confirmPassword')
            ->modalHeading(__('filament-passkeys::passkeys.confirm_password_heading'))
            ->modalDescription(__('filament-passkeys::passkeys.confirm_password_description'))
            ->modalSubmitActionLabel(__('filament-passkeys::passkeys.confirm_password'))
            ->schema($this->passwordConfirmationSchema())
            ->action(function (): void {
                session()->passwordConfirmed();

                $this->dispatch('filament-passkeys-password-confirmed');
            });
    }

    public function needsPasswordConfirmation(): bool
    {
        if (! $this->usesPasswordConfirmationMiddleware()) {
            return false;
        }

        $confirmedAt = Date::now()->unix() - (int) session('auth.password_confirmed_at', 0);
        $timeout = (int) Config::get('auth.password_timeout', 10800);

        return $confirmedAt > $timeout;
    }

    public function deletePasskey(int|string $passkeyId): void
    {
        $user = $this->currentUser();

        /** @var Passkey $passkey */
        $passkey = LaravelPasskeys::passkeyModel()::query()->findOrFail($passkeyId);

        abort_unless((string) $passkey->user_id === (string) $user->getKey(), 403);

        app(DeletePasskey::class)($user, $passkey);

        Notification::make()
            ->title(__('filament-passkeys::passkeys.deleted_notification_title'))
            ->success()
            ->send();
    }

    public function passkeyCreated(): void
    {
        $this->reset('name');

        Notification::make()
            ->title(__('filament-passkeys::passkeys.created_notification_title'))
            ->success()
            ->send();
    }

    public function passkeyAlreadyExists(): void
    {
        Notification::make()
            ->title(__('filament-passkeys::passkeys.already_exists_notification_title'))
            ->danger()
            ->send();
    }

    public function render(): View
    {
        return view('filament-passkeys::livewire.passkeys', data: [
            'passkeys' => $this->passkeys(),
        ]);
    }

    private function currentUser(): PasskeyUser
    {
        $user = Auth::guard(Config::string('passkeys.guard', 'web'))->user();

        if (! $user instanceof Authenticatable) {
            throw new RuntimeException('A user must be authenticated to manage passkeys.');
        }

        if (! $user instanceof PasskeyUser) {
            throw new RuntimeException('User model must implement the Laravel PasskeyUser contract.');
        }

        return $user;
    }

    /**
     * @return Collection<int, Passkey>
     */
    private function passkeys(): Collection
    {
        return $this->currentUser()
            ->passkeys()
            ->latest()
            ->get();
    }

    private function usesPasswordConfirmationMiddleware(): bool
    {
        foreach ((array) Config::get('passkeys.management_middleware', ['password.confirm']) as $middleware) {
            if (! is_string($middleware)) {
                continue;
            }

            if ($middleware === 'password.confirm' || str_starts_with($middleware, 'password.confirm:')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, TextInput>
     */
    private function passwordConfirmationSchema(): array
    {
        return [
            TextInput::make('password')
                ->label(__('filament-passkeys::passkeys.password'))
                ->password()
                ->revealable()
                ->required()
                ->autocomplete('current-password')
                ->currentPassword(guard: Config::string('passkeys.guard', 'web')),
        ];
    }
}
