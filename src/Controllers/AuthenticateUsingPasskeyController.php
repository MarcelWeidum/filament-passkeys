<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys\Controllers;

use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelPasskeys\Http\Controllers\AuthenticateUsingPasskeyController as BaseAuthenticateUsingPasskeyController;

final class AuthenticateUsingPasskeyController extends BaseAuthenticateUsingPasskeyController
{
    protected function logInAuthenticatable(Authenticatable $authenticatable, bool $remember = false): self
    {
        Filament::auth()->login($authenticatable, $remember);

        Session::regenerate();

        return $this;
    }
}
