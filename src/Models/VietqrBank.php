<?php

namespace Mr4Lc\VietQr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VietqrBank extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $table = 'vietqr_banks';
}
