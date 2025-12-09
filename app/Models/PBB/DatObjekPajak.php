<?php

namespace App\Models\PBB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DatObjekPajak extends Model
{
    use Compoships;
    protected $table = 'PBB.DAT_OBJEK_PAJAK';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;

    public function datSubjekPajak()
    {
        return $this->belongsTo(DatSubjekPajak::class, 'subjek_pajak_id', 'subjek_pajak_id');
    }

    public function nop(): Attribute
    {
        return Attribute::get(fn() => value(
            $this->kd_propinsi . '.' .
                $this->kd_dati2 . '.' .
                $this->kd_kecamatan . '.' .
                $this->kd_kelurahan . '.' .
                $this->kd_blok . '-' .
                $this->no_urut . '.' .
                $this->kd_jns_op
        ));
    }
    public function alamatLengkap(): Attribute
    {
        return Attribute::get(
            fn() => (string)  str($this->jalan_op)
                ->append(' ', $this->blok_kav_no_op)
                ->append(' RT/RW ', $this->rt_op, '/', $this->rw_op)
        );
    }
}
