<?php

return [
    'name' => env('ADMIN_NAME', 'Primary Admin'),
    'email' => env('ADMIN_EMAIL', 'admin@example.com'),
    'mobile' => env('ADMIN_MOBILE', '01700000000'),
    'intake' => (int) env('ADMIN_INTAKE', 1),
    'password_hash' => env('ADMIN_PASSWORD_HASH'),
];
