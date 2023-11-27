VietQR extension for laravel-admin
======

This is a `laravel` component that integrates [VietQR payment].
- Get consumer account information from QR Code standard.
- Create QR Code.

Note:
- This component don't using VietQR API, this component create QR CODE with EMVco.
- Data tested with TPBank.
- Version 0.0.1: can't create consumer account information, you need get this data from a sample QR Code.

## Screenshot

## Installation
```bash
composer require mr4-lc/vietqr
php artisan vendor:publish --tag=mr4-lc-vietqr --force
```

## Configuration
```php
return [
    'logo' => 'logo.png',
    'defaut' => [
        'transaction_currency' => '704',
        'country_code' => 'VN',
    ],
];
```

## Database
Table vietqr_informations

## Usage
```blade
<x-mr4-lc.consumer_account_information />
```
Hidden input: Random id

![Screenshot 2023-11-27 at 16 05 37](https://github.com/han48/mr4-lc.vietqr/assets/27817127/b6951e09-2916-4235-8652-0206e9c7be27)

```blade
<x-mr4-lc.consumer_account_information id="test" />
<input type="text" class="form-control" id="test">
```
![Screenshot 2023-11-27 at 16 05 51](https://github.com/han48/mr4-lc.vietqr/assets/27817127/d2806912-e47d-4b8b-a0e9-81a07b55b82f)

```blade
<x-mr4-lc.viet-qr />
```
![Screenshot 2023-11-27 at 16 06 14](https://github.com/han48/mr4-lc.vietqr/assets/27817127/5536377b-db20-4ce4-b74d-1854fcd0afff)

API:
```
{{host}}api/vietqr
```
```
{{host}}api/consumer-account-information
```

## License
Licensed under The [MIT License (MIT)](https://github.com/han48/mr4-lc.vietqr/blob/main/LICENSE).
