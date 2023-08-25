<?php

return [

    'title' => 'ثبت نام',

    'heading' => 'ثبت نام کنید',

    'actions' => [

        'login' => [
            'before' => 'یا',
            'label' => 'وارد حساب کاربریتان شوید',
        ],

        'request_password_reset' => [
            'label' => 'رمز عبور خود را فراموش کرده‌اید؟',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'آدرس ایمیل',
        ],

        'password' => [
            'label' => 'رمز عبور',
        ],

        'remember' => [
            'label' => 'مرا به خاطر بسپار',
        ],

        'actions' => [

            'register' => [
                'label' => 'ثبت نام',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'مشخصات واردشده با اطلاعات ما سازگار نیست.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'شما بیش از حد مجاز درخواست ورود داشته‌اید. لطفاً :seconds ثانیه دیگر تلاش کنید.',
        ],

    ],

];
