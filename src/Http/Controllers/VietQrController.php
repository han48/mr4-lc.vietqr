<?php

namespace Mr4Lc\VietQr\Http\Controllers;

use App\Http\Controllers\Controller;
use Mr4Lc\VietQr\Models\VietqrInformation;

class VietQrController extends Controller
{
    public function generateVietQr()
    {
        $validated = request()->validate(config('mr4vietqr.validation'));
        $validated = VietqrInformation::StandardValidatedData($validated);
        $logo_name = config('mr4vietqr.logo', 'logo.png');
        if (file_exists(public_path($logo_name))) {
            $validated['logo'] = $logo_name;
        }
        $vietQrInformation = VietqrInformation::find($validated['account_id']);
        $data = $vietQrInformation->generatePaymentCodeFromArray($validated);

        return response($data, 200);
    }

    public function generateVietQrEncode()
    {
        $validated = request()->validate(config('mr4vietqr.validation'));
        $validated = VietqrInformation::StandardValidatedData($validated);
        $vietQrInformation = VietqrInformation::find($validated['account_id']);
        $data = $vietQrInformation->createPaymentCodeFromArray($validated);

        return response([
            'code' => $data,
        ], 200);
    }

    public function generateVietQrDecode()
    {
        $validated = request()->validate([
            'data' => 'required|string',
        ]);
        try {
            $vietQrInformation = VietqrInformation::GetVietQrInformation(request()->get('data'));
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

    public function generateVietQrDetech()
    {
        $validated = request()->validate([
            'image' => 'required|image',
        ]);
        try {
            $file = $validated['image'];
            $vietQrInformation = VietqrInformation::GetVietQrInformationFromImage($file, request()->file('image')->getClientOriginalName());
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
