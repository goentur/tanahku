<?php

namespace App\Models\PBB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class DatOpBangunan extends Model
{
    use Compoships;
    protected $table = 'PBB.DAT_OP_BANGUNAN';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;
}
