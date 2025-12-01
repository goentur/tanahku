<?php

namespace App\Models\BPHTB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DatWajibPajak extends Model
{
    use Compoships;
    protected $table = 'BPHTB.DAT_WAJIB_PAJAK';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
