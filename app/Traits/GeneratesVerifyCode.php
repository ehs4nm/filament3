<?php 

namespace App\Traits;

use App\Notifications\SendMobileVerificationCodeNotification;
use Carbon\Carbon;
use Exception;

trait GeneratesVerifyCode
{

    protected function generatesVerifyCode($user)
    {
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        $user->update(['verify_code' => $code]);
        $notification = new SendMobileVerificationCodeNotification($code);
        $notification->verificationCode = $code;
        try {
            $status = $user->notify($notification);
        }
        catch (Exception $e) {
            return;
        }

        return $status;
    }

    protected function verifyCode($user, $token)
    {  
        if($user->verify_code === $token) {
            $user->update(['mobile_verified_at' => Carbon::now()]);
            return true;
        }
        return false;
    }
}
