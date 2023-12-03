VietQR extension for laravel-admin
======

This is a `laravel` component that integrates [VietQR payment].
- Get consumer account information from QR Code standard.
- Create QR Code.

Note:
- This component don't using VietQR API, this component create QR CODE with EMVco.
- Data tested with TPBank.

## Screenshot

## Installation
```bash
composer require mr4-lc/vietqr
php artisan vendor:publish --tag=mr4-lc-vietqr --force
php artisan migrate
php artisan vietqr:seed 
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
Table vietqr_banks

## Usage
```blade
<x-mr4-lc.consumer_account_information />
```
<img width="510" alt="Screenshot 2023-12-03 at 21 03 14" src="https://github.com/han48/mr4-lc.vietqr/assets/27817127/39a56680-339e-469e-94bd-b23b11b0cc77">

```blade
<x-mr4-lc.consumer_account_information id="tpb" />
<input type="text" class="form-control" id="tpb_account">
```
<img width="505" alt="Screenshot 2023-12-03 at 21 03 25" src="https://github.com/han48/mr4-lc.vietqr/assets/27817127/0dbc5bae-0cfd-4ba4-ad9d-6fba28cd8096">

```blade
<x-mr4-lc.consumer_account_information id="tpb2" />
<input type="text" class="form-control" id="tpb2_account">
<img id="tpb2_bank-logo_img" width="128">
```
<img width="509" alt="Screenshot 2023-12-03 at 21 03 34" src="https://github.com/han48/mr4-lc.vietqr/assets/27817127/14b98834-c92c-4639-b818-ef170f9347c6">

```blade
<x-mr4-lc.viet-qr />
<x-mr4-lc.viet-qr id="mr4"/>
```
![Screenshot 2023-12-03 at 21 04 47](https://github.com/han48/mr4-lc.vietqr/assets/27817127/4be2b24f-9ca5-425c-9ba2-1f59ba84b040)

API:
```json
Request:
{
    "account_id": 1,
    "transaction_amount": 180000,
    "message": "Xin chào",
    "transaction_id": "HD0123456",
}

API: {{host}}api/vietqr

Response:
{
    "code": "00020101021238550010A000000727012500069704230111012345678900208QRIBFTTA530370454061800005802VN62260109HD01234560809Xin chào6304A240"
    "qr": "data:image/png;base64"
}

API: {{host}}api/vietqr_encode

Response:
{
    "code": "00020101021238550010A000000727012500069704230111012345678900208QRIBFTTA530370454061800005802VN62260109HD01234560809Xin chào6304A240"
}
```

```json
Request:
{
    "data": "00020101021238550010A000000727012500069704230111012345678910208QRIBFTTA530370454061800005802VN62260109HD01234560809Xin chào630496FB"
}

API: {{host}}api/vietqr_detech


Request:
{
    "image": "FILE IMAGE"
}

API: {{host}}api/vietqr_decode

Response:
{
    "data": {
        "account": "01234567891",
        "bank": {
            "id": 10,
            "code": "TPB",
            "bin": "970423",
            "name": "Ngân hàng TMCP Tiên Phong",
            "shortName": "TPBank",
            "logo": "https://api.vietqr.io/img/TPB.png",
            "transferSupported": 1,
            "lookupSupported": 1,
            "support": 3,
            "isTransfer": 1,
            "swift_code": "TPBVVNVX",
            "status": 1,
            "created_at": "2023-11-30T03:55:56.000000Z",
            "updated_at": "2023-11-30T03:55:56.000000Z"
        },
        "serviceCode": {
            "id": 1,
            "name": "account",
            "value": "QRIBFTTA",
            "status": 1,
            "created_at": null,
            "updated_at": null
        },
        "message": "Xin chào",
        "transaction_amount": 180000,
        "transaction_id": "HD0123456",
        "transaction_currency": "704",
        "country_code": "VN",
        "point_of_initiation_method": "12"
    }
}
```

## License
Licensed under The [MIT License (MIT)](https://github.com/han48/mr4-lc.vietqr/blob/main/LICENSE).
