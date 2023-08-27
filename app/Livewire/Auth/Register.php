<?php

namespace App\Livewire\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as AuthRegister;

class Register extends AuthRegister
{
    protected static string $view = 'filament-panels::pages.auth.register';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent()->label('نام و نام خانوادگی'),
                TextInput::make('mobile')
                    ->label('شماره موبایل')
                    ->required()
                    ->numeric()
                    ->minLength(10)
                    ->tel()
                    ->telRegex('^09\d{9}$')
                    ->mask('0999-999-9999')
                    ->placeholder('0912-345-6789')
                    ->unique($this->getUserModel())
                    ->autocomplete(),
                $this->getPasswordFormComponent()->label('رمزعبور'),
                $this->getPasswordConfirmationFormComponent()->label('تکرار رمزعبور'),
            ])
            ->statePath('data');
    }
}
