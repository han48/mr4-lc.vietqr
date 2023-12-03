<?php

namespace Mr4Lc\VietQr\Models\ForUser;

use Mr4Lc\VietQr\Models\VietqrServiceCode as ModelsVietqrServiceCode;

class VietqrServiceCode extends ModelsVietqrServiceCode
{
    protected $table = 'vietqr_service_codes';
    protected $hidden = [
        'id', 'status', 'created_at', 'updated_at',
    ];
}
