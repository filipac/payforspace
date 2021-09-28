<?php
return [
  'elrond_wallet_address' => env('ELROND_WALLET_ADDRESS'),
  'price' => 0.1,

  'elrond_gateway' => env('ELROND_GATEWAY_URL', 'https://devnet-gateway.elrond.com/'),
  'elrond_api' => env('ELROND_API_URL', 'https://devnet-api.elrond.com/'),

  'divison_number' => 1000000000000000000,

  'checking_method' => env('CHECKING_METHOD', \App\Enums\CheckingMethod::gateway()->value),
];
