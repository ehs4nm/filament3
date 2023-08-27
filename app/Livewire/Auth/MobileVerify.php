<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class MobileVerify extends RequestPasswordReset
{
    protected static string $view = 'filament-panels::pages.auth.password-reset.request-password-reset';
    public $mobile;

    public function __construct()
    {
        $this->mobile = session()->get('temporary_mobile');
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
        
        $user = User::where('mobile', $this->mobile)->first();

        if ($user === null) {
            Notification::make()
                ->title('لطفا دوباره سعی کنید!')
                ->danger()
                ->send();

            redirect(filament()->getRequestPasswordResetUrl());

            return;
        }
        
        if ($user->verify_code === $data['verify_code']) {
            Notification::make()
                ->title('موبایل شما تایید شد، لطفا منتظر بمانید!')
                ->success()
                ->send();

            $user->verifyMobile();
            Auth::login($user);
            redirect()->route('filament.admin.pages.dashboard');

            return;
        }
        
        if ($user->verify_code !== $data['verify_code']) {
            Notification::make()
            ->title('کد وارد شده اشتباه است، لطفا دوباره سعی کنید!')
            ->danger()
            ->send();
        }

        $this->form->fill();

        return;
    }
    
    protected function getRequestFormAction(): Action
    {
        return Action::make('request')
            ->label('بررسی')
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getVerifyCodeFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getVerifyCodeFormComponent(): Component
    {
        return TextInput::make('verify_code')
            ->label('کد تایید')
            ->required()
            ->numeric()
            ->length(4)
            ->mask('9-9-9-9')
            ->placeholder('1-2-3-4')
            ->autofocus();
    }

    public function getTitle(): string
    {
        return 'کد تایید';
    }

    public function getHeading(): string
    {
        return 'کد تایید دریافت شده را وارد کنید.';
    }
}
