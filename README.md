VietQR extension for laravel-admin
======

This is a `laravel` component that integrates [VietQR payment].
- Get consumer account information from QR Code standard.
- Create QR Code.

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

```blade
<x-mr4-lc.consumer_account_information id="test" />
<input type="text" class="form-control" id="tpb">
```

```blade
<x-mr4-lc.viet-qr />
```

```blade
<x-mr4-lc.viet-qr id="mr4"/>
```


## License
Licensed under The [MIT License (MIT)](https://github.com/han48/mr4-lc.vietqr/blob/main/LICENSE).
