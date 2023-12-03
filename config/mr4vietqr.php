<?php

return [
    'logo' => 'logo.png',
    'defaut' => [
        'transaction_currency' => '704',
        'country_code' => 'VN',
        'logo_size' => 0.2,
        'aig' => 'A000000727',
    ],
    'payload_format_indicator' => '01',
    'api' => [
        'banks' => [
            'url' => 'https://api.vietqr.io/v2/banks',
            'columns' => [
                'name', 'code', 'bin', 'shortName', 'logo', 'transferSupported', 'lookupSupported', 'support', 'isTransfer', 'swift_code',
            ],
        ],
    ],
    'validation' => [
        'account_id' => 'required|string',
        'transaction_amount' => 'nullable',
        'message' => 'nullable',
        'transaction_id' => 'nullable',
        'service_code' => 'nullable',
        'point_of_initiation_method' => 'nullable',
        'currency' => 'nullable',
        'country' => 'nullable',
        'logo' => 'nullable',
        'logo_size' => 'nullable',
    ],
    'transaction_currencies' => [
        '392' => 'JPY',
        '410' => 'KRW',
        '458' => 'MYR',
        '156' => 'CNY',
        '360' => 'IDR',
        '608' => 'PHP',
        '702' => 'SGD',
        '704' => 'VND',
        '764' => 'THB',
    ],
    'country_codes' => [
        'JP' => 'Japan',
        'KR' => 'Korea',
        'MY' => 'Malaysia',
        'RC' => 'China',
        'RI' => 'Indonesia',
        'RP' => 'Philippines',
        'SG' => 'Singapore',
        'TH' => 'Thailand',
        'VN' => 'Viet Nam',
    ],
];
