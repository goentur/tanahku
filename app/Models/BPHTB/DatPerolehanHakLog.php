<?php

namespace App\Models\BPHTB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DatPerolehanHakLog extends Model
{
    use Compoships;
    protected $table = 'BPHTB.DAT_PEROLEHAN_HAK_LOG';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
