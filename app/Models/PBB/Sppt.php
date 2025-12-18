<?php

namespace App\Models\PBB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class Sppt extends Model
{
    use Compoships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'PBB.SPPT';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = [
        'kd_propinsi',
        'kd_dati2',
        'kd_kecamatan',
        'kd_kelurahan',
        'kd_blok',
        'no_urut',
        'kd_jns_op',
        'thn_pajak_sppt'
    ];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tgl_jatuh_tempo_sppt' => 'date:Y-m-d',
        'tgl_terbit_sppt' => 'date:Y-m-d',
        'tgl_cetak_sppt' => 'date:Y-m-d',
    ];

    protected $appends = ['pbb_denda_sppt'];

    protected $bulan_denda = 15;
    protected $persen_denda = 2;

    public function getBulanDenda()
    {
        return $this->bulan_denda;
    }

    public function setBulanDenda(int $value)
    {
        $this->bulan_denda = $value;
    }

    /**
     * @return int
     */
    public function getPersenDenda(): int
    {
        return $this->persen_denda;
    }

    /**
     * @param int $persen_denda
     */
    public function setPersenDenda(int $persen_denda): void
    {
        $this->persen_denda = $persen_denda;
    }

    public function getPbbDendaSpptAttribute($value)
    {
        $selisih_bulan = now()->diffInMonths($this->tgl_jatuh_tempo_sppt);
        $denda = 0;
        // @todo: ambil pembayaran sppt, jumlah pokok yg sudah dibayar
        // hasil pokok = jumlah pembayaran - pbb yg harus dibayar sppt
        $pokok = $this->pbb_yg_harus_dibayar_sppt;
        if ($selisih_bulan >= $this->getBulanDenda()) {
            $denda = $pokok * $this->getPersenDenda() / 100 * $this->getBulanDenda();
        } elseif ($selisih_bulan > 0) {
            $denda = $pokok * $this->getPersenDenda() / 100 * ceil($selisih_bulan);
        }

        return ceil($denda);
    }

    public function hitungPbbDendaSppt($fromDate)
    {
        $selisih_bulan = $fromDate->diffInMonths($this->tgl_jatuh_tempo_sppt);
        $denda = 0;
        // @todo: ambil pembayaran sppt, jumlah pokok yg sudah dibayar
        // hasil pokok = jumlah pembayaran - pbb yg harus dibayar sppt
        $pokok = $this->pbb_yg_harus_dibayar_sppt;
        if ($selisih_bulan >= $this->getBulanDenda()) {
            $denda = $pokok * $this->getPersenDenda() / 100 * $this->getBulanDenda();
        } elseif ($selisih_bulan > 0) {
            $denda = $pokok * $this->getPersenDenda() / 100 * ceil($selisih_bulan);
        }

        return ceil($denda);
    }

    public function getNjopkpSpptAttribute($value)
    {
        return $this->njop_sppt - $this->njoptkp_sppt;
    }

    /**
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatusPembayaran($query, $status = 1)
    {
        return $query->where('status_pembayaran_sppt', $status);
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByTahun($query, $order = 'asc')
    {
        return $query->orderBy('thn_pajak_sppt', $order);
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param null $operator
     * @param null $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTahun($query, $operator = null, $value = null)
    {
        return $query->where('thn_pajak_sppt', $operator, $value);
    }

    public function pembayaranSppt()
    {
        return $this->hasMany(PembayaranSppt::class, $this->primaryKey, $this->primaryKey);
    }
}
