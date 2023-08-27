<?php 

namespace App\Traits;

use App\Notifications\SendMobileVerificationCodeNotification;
use Carbon\Carbon;

trait GeneratesVerifyCode
{

    protected function generatesVerifyCode($user)
    {
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        $user->update(['verify_code' => $code]);

        $notification = new SendMobileVerificationCodeNotification($code);
        $notification->verificationCode = $code;

        $status = $user->notify($notification);

        return $status;
    }

    protected function verifyCode($user)
    {      
        $user->update(['mobile_verified_at' => Carbon::now()]);
        return true;
    }
}
