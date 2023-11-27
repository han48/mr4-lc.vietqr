<?php

namespace Mr4Lc\VietQr\Http\Controllers;

use App\Http\Controllers\Controller;
use Mr4Lc\VietQr\Models\VietqrInformation;

class VietQrController extends Controller
{
    public function generateVietQr()
    {
        $validated = request()->validate([
            'account_id' => 'required|string',
            'transaction_amount' => 'required|numeric',
            'message' => 'required|string|max:19',
            'transaction_currency' => 'nullable',
            'country_code' => 'nullable',
        ]);

        $logo = null;
        $logo_name = config('mr4vietqr.logo', 'logo.png');
        if (file_exists(public_path($logo_name))) {
            $logo = $logo_name;
        }

        $vietQrInformation = VietqrInformation::find($validated['account_id']);
        $data = $vietQrInformation->generatePaymentCode(
            $validated['transaction_amount'],
            $validated['message'],
            array_key_exists('transaction_currency', $validated) ? $validated['transaction_currency'] : VietqrInformation::CURRENCY_CODE,
            array_key_exists('country_code', $validated) ? $validated['country_code'] : VietqrInformation::COUNTRY_CODE,
            $logo
        );

        return response($data, 200);
    }

    public function getConsumerAccountInformation()
    {
        $validated = request()->validate([
            'image' => 'required|image',
        ]);
        try {
            $file = $validated['image'];
            $vietQrInformation = VietqrInformation::GetVietQrInformation($file, request()->file('image')->getClientOriginalName());
            return response([
                'data' => isset($vietQrInformation) ? $vietQrInformation : __('mr4lc-vietqr.qr_code.error', ['err' => __('mr4lc-vietqr.qr_code.message')]),
            ], isset($vietQrInformation) ? 200 : 400);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return response([
                'data' => $message,
            ], 400);
        }
    }
}
