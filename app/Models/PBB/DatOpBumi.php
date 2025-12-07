<?php

namespace App\Models\PBB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class DatOpBumi extends Model
{
    use Compoships;
    protected $table = 'PBB.DAT_OP_BUMI';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;
}
