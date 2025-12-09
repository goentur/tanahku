<?php

namespace App\Models\PBB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DatSubjekPajak extends Model
{
    use Compoships;
    protected $table = 'PBB.DAT_SUBJEK_PAJAK';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;


    public function alamatLengkap(): Attribute
    {
        return Attribute::get(
            fn() => (string)  str($this->jalan_wp)
                ->append(' ', $this->blok_kav_no_wp)
                ->append(' RT/RW ', $this->rt_wp, '/', $this->rw_wp)
                ->append(', ', $this->kelurahan_wp)
                ->append(', ', $this->kota_wp)
        );
    }
}
