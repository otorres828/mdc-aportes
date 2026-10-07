<?php

return [
    'api_url' => env('CONTRIBUTIONS_API_URL', ''),
    'cache_days' => (int) env('CONTRIBUTIONS_CACHE_DAYS', 30),
    'demo' => env('CONTRIBUTIONS_DEMO', true),
    'bank' => env('CHURCH_BANK', 'Banco Mercantil'),
    'bank_code' => env('CHURCH_BANK_CODE', '0105'),
    'holder' => env('CHURCH_ACCOUNT_HOLDER', 'Iglesia Mansion de Cristo'),
    'rif' => env('CHURCH_RIF', 'J-00000000-0'),
    'phone' => env('CHURCH_PHONE', '0414-0000000'),
    'account' => env('CHURCH_ACCOUNT', '0105 0000 00 0000000000'),
    'account_type' => env('CHURCH_ACCOUNT_TYPE', 'Corriente'),
    'banks' => ['Banesco', 'Banco de Venezuela', 'Banco Mercantil', 'BBVA Provincial', 'Banco Nacional de Crédito', 'Bancamiga', 'Banco del Tesoro', 'Banco Exterior', 'Banco Caroní', 'Banco Bicentenario', 'BFC Banco Fondo Común', '100% Banco', 'Banco Venezolano de Crédito', 'Banco Plaza', 'Banplus', 'Otro banco'],
];
