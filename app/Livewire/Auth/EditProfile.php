<?php

namespace App\Livewire\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as ProfilePage;
use Illuminate\Validation\ValidationException;

class EditProfile extends ProfilePage
{
    public $phone = '';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getMobileFormComponent(),
                $this->getEmailFormComponent(),
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
            ->unique(ignoreRecord: true)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/edit-profile.form.email.label'))
            ->email()
            ->maxLength(255)
            ->unique(ignoreRecord: true);
    }
}
