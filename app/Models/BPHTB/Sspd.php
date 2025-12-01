<?php

namespace App\Models\BPHTB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class Sspd extends Model
{
    use Compoships;
    protected $table = 'BPHTB.SSPD';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public function skpdkb()
    {
        return $this->belongsTo(
            Skpdkb::class,
            ['kd_tahun', 'kd_bundel', 'kd_no_urut'],
            ['tahun_skpdkb', 'bundel_skpdkb', 'no_urut_skpdkb']
        );
    }

    public function sptpd()
    {
        return $this->belongsTo(
            Sptpd::class,
            ['kd_tahun', 'kd_bundel', 'kd_no_urut'],
            ['tahun_sptpd', 'bundel_sptpd', 'no_urut_sptpd']
        );
    }
}
