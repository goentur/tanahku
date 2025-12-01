<?php

namespace App\Models\BPHTB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DatPerolehanHak extends Model
{
    use Compoships;
    protected $table = 'BPHTB.DAT_PEROLEHAN_HAK';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function refJph()
    {
        return $this->belongsTo(RefJph::class, 'ref_jph_id');
    }

    public function sptpd()
    {
        return $this->hasOne(Sptpd::class, 'dat_perolehan_hak_id');
    }

    public function ppat()
    {
        return $this->belongsTo(Ppat::class, 'ppat_id');
    }

    public function wpPenerimaHak()
    {
        return $this->belongsTo(DatWajibPajak::class, 'wp_penerima_hak_id');
    }

    public function skpdkb()
    {
        return $this->hasOneThrough(
            Skpdkb::class,
            Sptpd::class,
            'dat_perolehan_hak_id',
            'sptpd_id',
            'id', // dat_perolehan_hak.id
            'id' // sptpd.id
        );
    }

    public function datPemeriksaan()
    {
        return $this->hasOne(DatPemeriksaan::class, 'dat_perolehan_hak_id');
    }

    public function nomor(): Attribute
    {
        return Attribute::get(fn() => value(
            ($this->tahun_perolehan ?? now()->year) . '.'
                . ($this->bundel_perolehan ?? (now()->month . $this->ref_jph_id)) . '.'
                . ($this->no_urut_perolehan ?? 'XXX')
        ));
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
}
