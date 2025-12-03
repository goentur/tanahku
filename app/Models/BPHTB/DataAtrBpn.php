<?php

namespace App\Models\BPHTB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class DataAtrBpn extends Model
{
    use Compoships;
    protected $table = 'BPHTB.DATA_ATR_BPN';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;
}
