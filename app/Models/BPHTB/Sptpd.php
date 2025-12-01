<?php

namespace App\Models\BPHTB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class Sptpd extends Model
{
    use Compoships;
    protected $table = 'BPHTB.SPTPD';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function sspd()
    {
        return $this->hasOne(
            Sspd::class,
            ['kd_tahun', 'kd_bundel', 'kd_no_urut'],
            ['tahun_sptpd', 'bundel_sptpd', 'no_urut_sptpd']
        );
    }

    public function skpdkb()
    {
        return $this->hasOne(Skpdkb::class, 'sptpd_id');
    }

    public function refJph()
    {
        return $this->belongsTo(RefJph::class, 'ref_jph_id');
    }
}
