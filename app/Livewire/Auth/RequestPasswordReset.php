<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Forms\Get;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as PasswordResetRequestPasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class RequestPasswordReset extends PasswordResetRequestPasswordReset
{
    protected static string $view = 'filament-panels::pages.auth.password-reset.request-password-reset';
    public $mobile;
    public $verify_code;
    public bool $mobileWasWrongShowLink = false;
    public bool $hideMobileComponent = false;

    protected function getRequestFormAction(): Action
    {
        return Action::make('request')
            ->label($this->mobile ? 'بررسی' : 'ارسال پیامک')
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

        $user = User::where('mobile', $this->mobile)->first();

        if ($user === null) {
            Notification::make()
                ->title('شماره موبایل وارد شده اشتباه است!')
                ->danger()
                ->send();

            return;
        }

        if(strlen($this->verify_code) === 4) {
            if($user) {
                if($user->verifyMobile($this->verify_code)) {
                    Notification::make()
                        ->title('شماره موبایل شما تایید شد!')
                        ->success()
                        ->send();
                    $this->hideMobileComponent = true;
                    Auth::login($user);
                    redirect()->route('filament.admin.pages.dashboard');
                }
                else {
                    Notification::make()
                        ->title('کد وارد شده اشتباه است!')
                        ->danger()
                        ->send();
                }
                return;
            }
        }

        if(strlen($this->mobile) === 11) {
            if($user) {
                $user->sendVerificationCode($user);
                Notification::make()
                    ->title('کد تایید برای شما ارسال شد!')
                    ->success()
                    ->send();
                $this->hideMobileComponent = true;
                return;
            }
        }

        $this->form->fill();

        // session()->flash('temporary_mobile', $data['mobile']);
        // redirect()->route('verify.mobile');
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getMobileFormComponent(),
                $this->getVerifyCodeFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getVerifyCodeFormComponent(): Component
    {
        return TextInput::make('verify_code')
            ->label('کد تایید')
            // ->required()
            ->numeric()
            ->length(4)
            ->mask('9-9-9-9')
            ->placeholder('1-2-3-4')
            ->hidden(! $this->hideMobileComponent)
            ->live()
            ->afterStateUpdated(function ($state) {
                if(strlen($state) === 4) {
                    $this->verify_code = $state;
                }
            })
            ->hint($this->mobileWasWrongShowLink ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()"> {{ \'وارد کردن شماره موبایل\' }}</x-filament::link>')) : null)
            ->autofocus();
    }

    protected function getMobileFormComponent(): Component
    {
        return TextInput::make('mobile')
            ->label('شماره موبایل')
            ->required()
            ->numeric()
            ->minLength(10)
            ->telRegex('/^09\d{9}$/')
            ->mask('09999999999')
            ->placeholder('0912-345-6789')
            ->autocomplete()
            ->live()
            ->afterStateUpdated(function ($state) {
                if(strlen($state)=== 11) {
                    $this->mobile = $state;
                    // $this->hideMobileComponent = true;
                    // $this->request();
                }
            })
            ->hidden($this->hideMobileComponent)
            ->autofocus();
    }
}
