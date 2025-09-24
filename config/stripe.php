<?php

return [
    'payment_methods' => [
        'stripe' => [
            'title' => 'Stripe',
            'description' => 'Pay securely with your credit card via Stripe',
            'class' => \Webkul\Payment\Payment\Stripe::class,
            'active' => true,
            'sort' => 1,
        ],
    ],
];
