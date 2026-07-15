<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatAtrbpn extends Model
{
    protected $table = 'PBB.DAT_ATRBPN';
    public $incrementing = false;
    protected $primaryKey = null;
    protected $fillable = ['kode_wilayah', 'nib', 'nop', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'sumber_data', 'status', 'nama_pemilik_awal', 'nama_pemilik_akhir', 'luas', 'jenis_hak', 'nomor_hak', 'lokasi', 'nama_sesuai', 'luas_sesuai', 'bangunan_sesuai', 'luas_bangunan', 'nop_gabungan', 'nop_pecah'];
}
