<?php

namespace App\Models\BPHTB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class Skpdkb extends Model
{
    use Compoships;
    protected $table = 'BPHTB.SKPDKB';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function sspd()
    {
        return $this->hasOne(
            Sspd::class,
            ['kd_tahun', 'kd_bundel', 'kd_no_urut'],
            ['tahun_skpdkb', 'bundel_skpdkb', 'no_urut_skpdkb']
        );
    }
}
