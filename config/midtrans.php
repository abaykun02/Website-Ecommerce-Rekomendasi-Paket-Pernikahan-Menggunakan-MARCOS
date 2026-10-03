<?php

return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'M983311722'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'MIDTRANS_SERVER_KEY_ANDA'),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'MIDTRANS_SERVER_KEY_ANDA'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
