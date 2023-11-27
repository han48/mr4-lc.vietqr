<?php

namespace Mr4Lc\VietQr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PHPZxing\PHPZxingDecoder;

class VietqrInformation extends Model
{
    use HasFactory;

    const MESSAGE_PREFIX = '0107NPS68690819';
    const MESSAGE_LENGTH = 34;
    const MESSAGE_PAD = ' ';
    const CURRENCY_CODE = '704';
    const COUNTRY_CODE = 'VN';

    protected $table = 'vietqr_informations';

    /**
     * VietQR format
     * message length: 19
     *
     * @var string
     */
    protected $vietqr_format = '00020101021238:consumer_account_information53:currency_code54:amount58:country_code62:message';
    protected $vietqr_hash = '63:crc';

    public function createCRC($str, $type = 'CRC-CCITT (0xFFFF)')
    {
        switch ($type) {
            case 'CRC-CCITT (0xFFFF)':
                // The PHP version of the JS str.charCodeAt(i)
                function charCodeAt($str, $i)
                {
                    return ord(substr($str, $i, 1));
                }

                $crc = 0xFFFF;
                $strlen = strlen($str);
                for ($c = 0; $c < $strlen; $c++) {
                    $crc ^= charCodeAt($str, $c) << 8;
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
        return preg_replace('/[^A-Za-z0-9\- ]/', '-', $string); // Removes special chars.
    }

    public function createPaymentCode($amount, $message, $currency = '704', $country = 'VN')
    {

        $consumer_account_information = $this->consumer_account_information;
        $consumer_account_information_length = str_pad(strlen($consumer_account_information), 2, '0', STR_PAD_LEFT);
        $consumer_account_information = $consumer_account_information_length . $consumer_account_information;

        $currency_code = $currency;
        $currency_code_length = str_pad(strlen($currency_code), 2, '0', STR_PAD_LEFT);
        $currency_code = $currency_code_length . $currency_code;

        $amount = $amount;
        $amount_length = str_pad(strlen($amount), 2, '0', STR_PAD_LEFT);
        $amount = $amount_length . $amount;

        $country = $country;
        $country_length = str_pad(strlen($country), 2, '0', STR_PAD_LEFT);
        $country = $country_length . $country;

        $message = static::VietnameseToASCII($message);
        $message = static::CleanMessage($message);
        $message = mb_convert_encoding($message, "ASCII");
        $message = str_replace("?", '-', $message);
        $message = substr(static::MESSAGE_PREFIX . $message, 0, static::MESSAGE_LENGTH);
        $message = str_pad($message, static::MESSAGE_LENGTH, static::MESSAGE_PAD, STR_PAD_RIGHT);
        $message_length = str_pad(strlen($message), 2, '0', STR_PAD_LEFT);
        $message = $message_length . $message;

        $result = __($this->vietqr_format, [
            'consumer_account_information' => $consumer_account_information,
            'currency_code' => $currency_code,
            'amount' => $amount,
            'country_code' => $country,
            'message' => $message,
        ]);

        $crc = $this->createCRC($result);
        $crc_length = str_pad(strlen($crc), 2, '0', STR_PAD_LEFT);
        $crc = $crc_length . $crc;

        $crc = __($this->vietqr_hash, [
            'crc' => $crc,
        ]);

        return $result . $crc;
    }

    public function generatePaymentCode($amount, $message, $currency = '704', $country = 'VN', $logo = null, $logo_size = 0.2)
    {
        $code = $this->createPaymentCode($amount, $message, $currency, $country);
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
        $datas['raw'] = $str;
        $tmp = $str;
        while (strlen($tmp) > 0) {
            $key = substr($tmp, 0, 2);
            $length = substr($tmp, 2, 2);
            $value = substr($tmp, 4, $length);
            $datas[$key] = $value;
            $tmp = substr($tmp, 2 + 2 + $length);
        }
        return $datas;
    }

    public static function GetVietQrInformation($file, $fileName = 'UNDEFINED')
    {
        $obj = new static();
        $decoder = new PHPZxingDecoder();
        $data = $decoder->decode($file);
        if ($data->isFound()) {
            $value = $data->getImageValue();
            $vietQr = static::VietQrDataAnalytic($value);
            $obj->datas = json_encode($vietQr);
            $obj->name = $fileName;
            $obj->consumer_account_information = $vietQr["38"];
            $obj->datas = json_encode($vietQr);
        } else {
            $obj = null;
        }
        return $obj;
    }
}
