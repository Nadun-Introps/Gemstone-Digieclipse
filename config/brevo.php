<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Brevo API Configuration
    |--------------------------------------------------------------------------
    */

    'api_key' => env('BREVO_API_KEY'),

    'sender' => [
        'email' => env('BREVO_SENDER_EMAIL', 'shop@example.com'),
        'name' => env('BREVO_SENDER_NAME', 'Shop'),
    ],

    'admin' => [
        'email' => env('BREVO_ADMIN_EMAIL', 'admin@example.com'),
        'name' => env('BREVO_ADMIN_NAME', 'Admin'),
    ],

    'templates' => [
        'payment_success' => 1, // Replace with actual Brevo template ID
        'payment_failed' => 2,  // Replace with actual Brevo template ID
        'outbid_notification' => 3, // Replace with actual Brevo template ID
    ],
];
