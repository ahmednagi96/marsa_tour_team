<?php



return [
    "payment_providers"=>[
        "tap"=>\App\Infrastructure\Payment\TapGateway::class,
    ],
    'provider' => env('PAYMENT_PROVIDER', 'tap'),
    "PAYMENT_PROVIDER"=>"tap",
    "TAP_HEADER"=>env('TAP_HEADER'),
    "TAP_URL"=>env('TAP_URL')

];