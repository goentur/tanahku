<?php

namespace App\Models\PBB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class PendataanSpop extends Model
{
    use Compoships;
    protected $table = 'PBB.PENDATAAN_SPOP';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;
}
