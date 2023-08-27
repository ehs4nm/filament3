<?php

namespace App\Notifications;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\Laravel\Message\KavenegarMessage;

class SendMobileVerificationCodeNotification extends Notification
{
    use Queueable;

    public $verificationCode;
    /**
     * Create a new notification instance.
     */
    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['kavenegar'];
    }

    /**
     * Get the Sms representation of the notification.
     */
    public function toKavenegar(object $notifiable)
    {
        return (new KavenegarMessage())->verifyLookup('notifyadmin', [$this->verificationCode]);
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
