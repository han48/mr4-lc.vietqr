<?php

namespace Mr4Lc\VietQr\Models\ForUser;

use Mr4Lc\VietQr\Models\VietqrBank as ModelsVietqrBank;

class VietqrBank extends ModelsVietqrBank
{
    protected $table = 'vietqr_banks';
    protected $hidden = [
        'id', 'status', 'created_at', 'updated_at', 'transferSupported', 'lookupSupported', 'support', 'isTransfer', 'swift_code',
    ];
}
