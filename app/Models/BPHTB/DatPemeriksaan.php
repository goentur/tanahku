<?php

namespace App\Models\BPHTB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DatPemeriksaan extends Model
{
    use Compoships;
    protected $table = 'BPHTB.DAT_PEMERIKSAAN';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
