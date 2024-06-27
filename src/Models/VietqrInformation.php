<?php

namespace Mr4Lc\VietQr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mr4Lc\VietQr\Models\ForUser\VietqrBank;
use Mr4Lc\VietQr\Models\ForUser\VietqrInformation as ForUserVietqrInformation;
use Mr4Lc\VietQr\Models\ForUser\VietqrServiceCode;
use Mr4Lc\VietQr\VietQrConsts;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PHPZxing\PHPZxingDecoder;

class VietqrInformation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'vietqr_informations';

    public function bank()
    {
        return $this->belongsTo(VietqrBank::class, 'vietqr_bank_id', 'id');
    }

    public function serviceCode()
    {
        return $this->belongsTo(VietqrServiceCode::class, 'vietqr_service_code_id', 'id');
    }

    public static function AddData($id, $data, $template = false)
    {
        if (!isset($data) || strlen($data) === 0) {
            return '';
        }
        $result = $id;
        if ($template) {
            $result = $result . $data;
        } else {
            $data_length = str_pad(strlen($data), 2, '0', STR_PAD_LEFT);
            $result = $result . $data_length . $data;
        }

        return $result;
    }

    public function charCodeAt($str, $i)
    {
        return ord(substr($str, $i, 1));
    }

    public function createCRC($str, $type = VietQrConsts::StandardCRC)
    {
        switch ($type) {
            case VietQrConsts::StandardCRC:
                $crc = 0xFFFF;
                $strlen = strlen($str);
                for ($c = 0; $c < $strlen; $c++) {
                    $crc ^= $this->charCodeAt($str, $c) << 8;
                    for ($i = 0; $i < 8; $i++) {
                        if ($crc & 0x8000) {
                            $crc = ($crc << 1) ^ 0x1021;
                        } else {
                            $crc = $crc << 1;
                        }
                    }
                }
                $hex = $crc & 0xFFFF;
                $hex = dechex($hex);
                $hex = strtoupper($hex);

                return substr($hex, -4);
            default:
                throw new \Exception('Not support CRC.');
        }
    }

    public static function VietnameseToASCII($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);

        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    public static function CleanMessage($string)
    {
        return preg_replace('/[^A-Za-z0-9\- ]/', '-', $string);
    }

    public function generateConsumerAccountInformation()
    {
        $result = '';

        $aid = static::AddData(VietQrConsts::AID, config('mr4vietqr.defaut.aig', VietQrConsts::AID_GUID));
        $serviceCode = static::AddData(VietQrConsts::ServiceCodeId, $this->serviceCode->value);

        $bank = static::AddData(VietQrConsts::BankId, $this->bank->bin);
        $account = static::AddData(VietQrConsts::BankAccount, $this->account);
        $beneficiaryAccount = static::AddData(VietQrConsts::BankAccount, $bank . $account);

        $result = $aid . $beneficiaryAccount . $serviceCode;
        return $result;
    }

    public function generateMessageData($message, $transactionId = null)
    {
        $result = '';
        $result = $result . static::AddData(VietQrConsts::AdditionalDataFieldBillNumberId, $transactionId);
        $result = $result . static::AddData(VietQrConsts::AdditionalDataFieldPurposeOfTransactionId, $message);;
        return $result;
    }

    public static function StandardValidatedData($validated)
    {
        if (!array_key_exists('transaction_amount', $validated) || $validated['transaction_amount'] === null) {
            $validated['transaction_amount'] = null;
        }
        if (!array_key_exists('message', $validated) || $validated['message'] === null) {
            $validated['message'] = null;
        }
        if (!array_key_exists('transaction_id', $validated) || $validated['transaction_id'] === null) {
            $validated['transaction_id'] = null;
        }
        if (!array_key_exists('point_of_initiation_method', $validated) || $validated['point_of_initiation_method'] === null) {
            $validated['point_of_initiation_method'] = VietQrConsts::PointOfInitiationMethodQRDynamic;
        }
        if (!array_key_exists('currency', $validated) || $validated['currency'] === null) {
            $validated['currency'] = config('mr4vietqr.defaut.transaction_currency', VietQrConsts::CurrencyCodeVND);
        }
        if (!array_key_exists('country', $validated) || $validated['country'] === null) {
            $validated['country'] = config('mr4vietqr.defaut.country_code', VietQrConsts::CountryCodeVN);
        }
        if (!array_key_exists('logo', $validated) || $validated['logo'] === null) {
            $validated['logo'] = null;
        }
        if (!array_key_exists('logo_size', $validated) || $validated['logo_size'] === null) {
            $validated['logo_size'] = config('mr4vietqr.defaut.logo_size', VietQrConsts::LogoSize);
        }
        return $validated;
    }

    public function createPaymentCodeFromArray($validated)
    {
        return $this->createPaymentCode(
            $validated['transaction_amount'],
            $validated['message'],
            $validated['transaction_id'],
            $validated['point_of_initiation_method'],
            $validated['currency'],
            $validated['country']
        );
    }

    public function createPaymentCode($amount, $message, $transactionId = null, $point_of_initiation_method = VietQrConsts::PointOfInitiationMethodQRDynamic, $currency = VietQrConsts::CurrencyCodeVND, $country = VietQrConsts::CountryCodeVN)
    {
        $result = '';
        $result = $result . static::AddData(VietQrConsts::PayloadFormatIndicatorId, config('mr4vietqr.payload_format_indicator', VietQrConsts::PayloadFormatIndicator));
        $result = $result . static::AddData(VietQrConsts::PointOfInitiationMethodId, $point_of_initiation_method);
        $result = $result . static::AddData(VietQrConsts::MerchantAccountInformationId, $this->generateConsumerAccountInformation());
        $result = $result . static::AddData(VietQrConsts::TransactionCurrencyId, $currency);
        $result = $result . static::AddData(VietQrConsts::TransactionAmountId, $amount);
        $result = $result . static::AddData(VietQrConsts::CountryCodeId, $country);
        $result = $result . static::AddData(VietQrConsts::AdditionalDataFieldTemplateId, $this->generateMessageData($message, $transactionId));
        $result = $result . substr(static::AddData(VietQrConsts::CRCId, "0000"), 0, -4);
        $crc = $this->createCRC($result);
        $result = $result . $crc;

        return $result;
    }

    public function generatePaymentCodeFromArray($validated)
    {
        return $this->generatePaymentCode(
            $validated['transaction_amount'],
            $validated['message'],
            $validated['transaction_id'],
            $validated['point_of_initiation_method'],
            $validated['currency'],
            $validated['country'],
            $validated['logo'],
            $validated['logo_size']
        );
    }

    public function generatePaymentCode($amount, $message, $transactionId = null, $point_of_initiation_method = VietQrConsts::PointOfInitiationMethodQRDynamic, $currency = VietQrConsts::CurrencyCodeVND, $country = VietQrConsts::CountryCodeVN, $logo = null, $logo_size = VietQrConsts::LogoSize)
    {
        $code = $this->createPaymentCode($amount, $message, $transactionId, $point_of_initiation_method, $currency, $country);
        if (isset($logo)) {
            $qr = QrCode::format('png')->size(500)->merge($logo, $logo_size, true)->generate($code);
        } else {
            $qr = QrCode::format('png')->size(500)->generate($code);
        }
        return [
            'code' => $code,
            'qr' => "data:image/png;base64," . base64_encode($qr),
        ];
    }

    public static function VietQrDataAnalytic($str)
    {
        $datas = [];
        $tmp = $str;
        while (strlen($tmp) > 0) {
            $key = substr($tmp, 0, 2);
            $length = substr($tmp, 2, 2);
            $value = substr($tmp, 4, $length);
            $datas[$key] = $value;
            $tmp = substr($tmp, 2 + 2 + $length);
        }
        $vietQr = new VietqrInformation();
        $vietQr->datas = $str;

        if (key_exists('00', $datas)) {
            $vietQr->payload_format_indicator = $datas['00'];
        }
        if (key_exists('01', $datas)) {
            $vietQr->point_of_initiation_method = $datas['01'];
            $vietQr->point_of_initiation_method_name = __('mr4lc-vietqr.point_of_initiation_methods.' . $datas['01']);
        }
        if (key_exists('38', $datas)) {
            $vietQr->consumer_account_information = $datas['38'];
            $consumerAccountInformationData = [];
            $tmp = $vietQr->consumer_account_information;
            while (strlen($tmp) > 0) {
                $key = substr($tmp, 0, 2);
                $length = substr($tmp, 2, 2);
                $value = substr($tmp, 4, $length);
                $consumerAccountInformationData[$key] = $value;
                $tmp = substr($tmp, 2 + 2 + $length);
            }
            $vietQr->consumer_account_information_data = $consumerAccountInformationData;
            if (key_exists('00', $consumerAccountInformationData)) {
                $vietQr->aig = $consumerAccountInformationData['00'];
            }
            if (key_exists('01', $consumerAccountInformationData)) {
                $vietQr->beneficiary_account = $consumerAccountInformationData['01'];

                $beneficiaryAccountData = [];
                $tmp = $vietQr->beneficiary_account;
                while (strlen($tmp) > 0) {
                    $key = substr($tmp, 0, 2);
                    $length = substr($tmp, 2, 2);
                    $value = substr($tmp, 4, $length);
                    $beneficiaryAccountData[$key] = $value;
                    $tmp = substr($tmp, 2 + 2 + $length);
                }
                $vietQr->beneficiary_account_data = $beneficiaryAccountData;
                if (key_exists('00', $beneficiaryAccountData)) {
                    $vietQr->vietqr_bank_bin = $beneficiaryAccountData['00'];
                    $vietQr->bank = VietqrBank::where('bin', $vietQr->vietqr_bank_bin)->first();
                    if (isset($vietQr->bank)) {
                        $vietQr->vietqr_bank_id = $vietQr->bank->id;
                    }
                }
                if (key_exists('01', $beneficiaryAccountData)) {
                    $vietQr->account = $beneficiaryAccountData['01'];
                }
            }
            if (key_exists('02', $consumerAccountInformationData)) {
                $vietQr->service_code = $consumerAccountInformationData['02'];
                $vietQr->serviceCode = VietqrServiceCode::where('value', $vietQr->service_code)->first();
                if (isset($vietQr->serviceCode)) {
                    $vietQr->vietqr_service_code_id = $vietQr->serviceCode->id;
                }
                $vietQr->service_code_name = __('mr4lc-vietqr.service_codes.' . $consumerAccountInformationData['02']);;
            }
        }
        if (key_exists('53', $datas)) {
            $vietQr->transaction_currency = $datas['53'];
            $vietQr->transaction_currency_name = config('mr4vietqr.transaction_currencies.' . $vietQr->transaction_currency, null);
        }
        if (key_exists('54', $datas)) {
            $vietQr->transaction_amount = floatval($datas['54']);
        }
        if (key_exists('58', $datas)) {
            $vietQr->country_code = $datas['58'];
            $vietQr->country_code_name = config('mr4vietqr.country_codes.' . $vietQr->country_code, null);
        }
        if (key_exists('62', $datas)) {
            $vietQr->additional_data_field = $datas['62'];
            $additionalDataFieldData = [];
            $tmp = $vietQr->additional_data_field;
            while (strlen($tmp) > 0) {
                $key = substr($tmp, 0, 2);
                $length = substr($tmp, 2, 2);
                $value = substr($tmp, 4, $length);
                $additionalDataFieldData[$key] = $value;
                $tmp = substr($tmp, 2 + 2 + $length);
            }
            $vietQr->additional_data_field_data = $additionalDataFieldData;
            if (key_exists('01', $additionalDataFieldData)) {
                $vietQr->transaction_id = $additionalDataFieldData['01'];
            }
            if (key_exists('08', $additionalDataFieldData)) {
                $vietQr->message = $additionalDataFieldData['08'];
            }
        }
        if (key_exists('63', $datas)) {
            $vietQr->crc = $datas['63'];
        }

        $result = ForUserVietqrInformation::where('account', $vietQr->account)->where('vietqr_bank_id', $vietQr->vietqr_bank_id)->where('vietqr_service_code_id', $vietQr->vietqr_service_code_id)->with(['bank', 'serviceCode'])->first();
        if (!isset($result)) {
            $result = new ForUserVietqrInformation([
                'account' => $vietQr->account,
                'vietqr_service_code_id' => $vietQr->vietqr_service_code_id,
                'vietqr_bank_id' => $vietQr->vietqr_bank_id,
                'datas' => $vietQr->datas,
            ]);
            $result->bank = $vietQr->bank;
            $result->serviceCode = $vietQr->serviceCode;
        }

        $result->message = $vietQr->message;
        $result->transaction_amount = $vietQr->transaction_amount;
        $result->transaction_id = $vietQr->transaction_id;
        $result->transaction_currency = $vietQr->transaction_currency;
        $result->country_code = $vietQr->country_code;
        $result->point_of_initiation_method = $vietQr->point_of_initiation_method;


        return $result;
    }

    public static function GetVietQrInformation($data)
    {
        $vietQr = static::VietQrDataAnalytic($data);
        return $vietQr;
    }

    public static function GetVietQrInformationFromImage($file, $fileName = VietQrConsts::FileName)
    {
        $obj = new static();
        $decoder = new PHPZxingDecoder();
        $data = $decoder->decode($file);
        if ($data->isFound()) {
            $value = $data->getImageValue();
            $obj = static::VietQrDataAnalytic($value);
        } else {
            $obj = null;
        }
        return $obj;
    }
}
