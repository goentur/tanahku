<?php

namespace App\Models\PBB;

use App\Casts\LeadingZero;
use App\Casts\Uppercase;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DatSubjekPajak extends Model
{
    use Compoships;
    protected $table = 'PBB.DAT_SUBJEK_PAJAK';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'subjek_pajak_id';
    protected $keyType = 'string';
    protected $fillable = [
        'subjek_pajak_id',
        'nm_wp',
        'jalan_wp',
        'blok_kav_no_wp',
        'rw_wp',
        'rt_wp',
        'kelurahan_wp',
        'kota_wp',
        'kd_pos_wp',
        'telp_wp',
        'npwp',
        'status_pekerjaan_wp',
    ];
    protected $casts = [
        'nm_wp' => Uppercase::class,
        'jalan_wp' => Uppercase::class,
        'blok_kav_no_wp' => Uppercase::class,
        'kelurahan_wp' => Uppercase::class,
        'kota_wp' => Uppercase::class,
        'rw_wp' => LeadingZero::class . ':2',
        'rt_wp' => LeadingZero::class . ':3',
    ];


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
