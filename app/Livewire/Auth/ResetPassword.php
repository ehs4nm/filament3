<?php

namespace App\Livewire\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\PasswordReset\ResetPassword as AuthResetPassword;

class ResetPassword extends AuthResetPassword
{
    protected static string $view = 'filament-panels::pages.auth.password-reset.rest-password-reset';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getMobileFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getMobileFormComponent(): Component
    {
        return TextInput::make('mobile')
            ->label('شماره موبایل')
            ->required()
            ->numeric()
            ->minLength(10)
            ->telRegex('/^09\d{9}$/')
            ->mask('0999-999-9999')
            ->placeholder('0912-345-6789')
            ->autocomplete()
            ->autofocus();
    }
}
