<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'GardenNGrow'),
    'currency' => 'BDT',
    'currency_symbol' => '৳',
    'default_locale' => 'bn',
    'supported_locales' => ['en', 'bn'],

    'free_shipping_threshold' => env('FREE_SHIPPING_THRESHOLD', 1500),

    'shipping' => [
        'dhaka_city' => 60,
        'dhaka_outside' => 100,
        'chittagong' => 120,
        'sylhet' => 130,
        'rajshahi' => 130,
        'khulna' => 130,
        'barisal' => 140,
        'rangpur' => 140,
        'mymensingh' => 120,
        'default' => 150,
    ],

    'order_prefix' => 'GNG',

    'low_stock_threshold' => 5,

    'per_page' => 12,
    'blog_per_page' => 9,

    'whatsapp_number' => env('WHATSAPP_NUMBER'),

    'cache' => [
        'products_ttl' => 3600,
        'categories_ttl' => 86400,
        'settings_ttl' => 86400,
    ],
];
