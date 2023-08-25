<?php

namespace App\Livewire\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as AuthLogin;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends AuthLogin
{
    protected static string $view = 'filament-panels::pages.auth.login';

    public $phone = '';
 
    // public function authenticate(): ?LoginResponse
    // {
    //     try {
    //         $this->rateLimit(5);
    //     } catch (TooManyRequestsException $exception) {
    //         throw ValidationException::withMessages([
    //             'email' => __('filament::login.messages.throttled', [
    //                 'seconds' => $exception->secondsUntilAvailable,
    //                 'minutes' => ceil($exception->secondsUntilAvailable / 60),
    //             ]),
    //         ]);
    //     }
 
    //     $data = $this->form->getState();
 
    //     if (! Filament::auth()->attempt([
    //         'phone' => $data['phone'],
    //         'password' => $data['password'],
    //     ], $data['remember'])) {
    //         throw ValidationException::withMessages([
    //             'phone' => __('filament::login.messages.failed'),
    //         ]);
    //     }
 
    //     session()->regenerate();
 
    //     return app(LoginResponse::class);
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('mobile')
                    ->label('شماره موبایل')
                    ->required()
                    ->numeric()
                    ->minLength(10)
                    ->tel()
                    ->telRegex('^09\d{9}$')
                    ->mask('0999-999-9999')
                    ->placeholder('0912-345-6789')
                    ->autocomplete(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }
}
