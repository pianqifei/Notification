<?php

return [
    'channel'=>[
        'email'=>[],
        'submail' => [
            'appid' => env('SMS_APPID', ''),
            'signature' => env('SMS_SIGNATURE', ''),
            'appid_international' => env('SMS_APPID_INTERNATIONAL', ''),
            'signature_international' => env('SMS_SIGNATURE_INTERNATIONAL', ''),
        ],
        'sign' => env('SMS_SIGN', ''),
        'timeout'=>10,
    ],
];
