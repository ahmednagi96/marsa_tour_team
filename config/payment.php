<?php



return [
    "payment_providers"=>[
        "tap"=>\App\Infrastructure\Payment\TapGateway::class,
    ],
    'provider' => env('PAYMENT_PROVIDER', 'tap'),
];