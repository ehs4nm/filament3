<?php

return [

    'title' => 'فراموشی رمزعبور',

    'heading' => 'رمزعبور خود را تغییر دهید',

    'form' => [

        'mobile' => [
            'label' => 'شماره موبایل',
        ],

        'email' => [
            'label' => 'آدرس ایمیل',
        ],

        'password' => [
            'label' => 'رمزعبور',
            'validation_attribute' => 'رمزعبور',
        ],

        'password_confirmation' => [
            'label' => 'تایید رمزعبور',
        ],

        'actions' => [

            'reset' => [
                'label' => 'تغییر رمزعبور',
            ],

        ],

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'تعداد درخواست زیاد است',
            'body' => 'لطفا :seconds ثانیه صبر کنید.',
        ],

    ],

];
