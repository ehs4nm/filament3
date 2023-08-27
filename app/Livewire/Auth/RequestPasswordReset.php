<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as PasswordResetRequestPasswordReset;

class RequestPasswordReset extends PasswordResetRequestPasswordReset
{
    protected static string $view = 'filament-panels::pages.auth.password-reset.request-password-reset';
    protected $mobile;

    protected function getRequestFormAction(): Action
    {
        return Action::make('request')
            ->label('ارسال پیامک')
            ->submit('request');
    }

    public function loginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('برگشت به صفحه ورود')
            ->icon(match (__('filament-panels::layout.direction')) {
                'rtl' => 'heroicon-m-arrow-right',
                default => 'heroicon-m-arrow-left',
            })
            ->url(filament()->getLoginUrl());
    }

    public function request(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();
        
        $user = User::where('mobile', $data['mobile'])->first();

        if ($user === null) {
            Notification::make()
                ->title('شماره موبایل وارد شده اشتباه است!')
                ->danger()
                ->send();

            return;
        }

        $this->form->fill();

        session()->flash('temporary_mobile', $data['mobile']);
        redirect()->route('verify.mobile');
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getMobileFormComponent(),
            ])
            ->statePath('data');
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
