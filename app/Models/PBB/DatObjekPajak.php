<?php

namespace App\Models\PBB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class DatObjekPajak extends Model
{
    use Compoships;
    protected $table = 'PBB.DAT_OBJEK_PAJAK';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;
}
