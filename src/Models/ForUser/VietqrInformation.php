<?php

namespace Mr4Lc\VietQr\Models\ForUser;

use Mr4Lc\VietQr\Models\VietqrInformation as ModelsVietqrInformation;

class VietqrInformation extends ModelsVietqrInformation
{
    protected $table = 'vietqr_informations';
    protected $hidden = [
        'id', 'datas', 'status', 'hash_type', 'created_at', 'updated_at', 'vietqr_service_code_id', 'vietqr_bank_id',
    ];

    public function bank()
    {
        return $this->belongsTo(VietqrBank::class, 'vietqr_bank_id', 'id');
    }

    public function serviceCode()
    {
        return $this->belongsTo(VietqrServiceCode::class, 'vietqr_service_code_id', 'id');
    }
}
