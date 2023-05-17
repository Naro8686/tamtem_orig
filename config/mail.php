<?php

return [
/*
    | Laravel supports both SMTP and PHP's "mail" function as drivers for the
    | sending of e-mail. You may specify which one you're using throughout
    | your application here. By default, Laravel is setup for SMTP mail.
    |
    | Supported: "smtp", "sendmail", "mailgun", "mandrill", "ses",
    |            "sparkpost", "log", "array"
*/
    'driver' => env('MAIL_DRIVER', 'smtp'),
/*
    'driver' => env('MAIL_DRIVER', 'sendmail'),
*/
    'host' => env('MAIL_HOST', 'mail.krivolapov.com'),
    'port' => env('MAIL_PORT', '587'),
    'address' => env('MAIL_USERNAME', 'testuser'),
    'name' => env('MAIL_FROM_NAME', 'testuser@krivolapov.com'),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME', 'testuser@krivolapov.com'),
    'password' => env('MAIL_PASSWORD', 'WYuit0eazrr9i6XvdTcm'),
    'sendmail' => '/usr/sbin/sendmail -bs',
/*
    'sendmail' => '/usr/sbin/sendmail  -t -i',
*/
    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],
];
