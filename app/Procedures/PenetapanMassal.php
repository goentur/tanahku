<?php

namespace App\Procedures;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PenetapanMassal
{
    /**
     * Menjalankan prosedur Oracle "PENETAPAN_MASSAL" untuk menetapkan data SPPT secara massal.
     *
     * Prosedur ini mengisi atau memperbarui data objek pajak (tanah & bangunan) beserta informasi subjek pajak,
     * jatuh tempo, tanggal terbit, dan data administrasi lainnya.
     *
     * @param string  $kd_propinsi           Kode propinsi
     * @param string  $kd_dati2              Kode kabupaten/kota
     * @param string  $kd_kecamatan          Kode kecamatan
     * @param string  $kd_kelurahan          Kode kelurahan
     * @param string  $kd_blok               Kode blok
     * @param string  $no_urut               Nomor urut objek
     * @param string  $kd_jns_op             Kode jenis objek pajak
     * @param string  $no_persil             Nomor persil
     * @param int     $njop_bumi             NJOP tanah
     * @param int     $njop_bng              NJOP bangunan
     * @param int     $luas_bumi             Luas tanah (m²)
     * @param int     $luas_bng              Luas bangunan (m²)
     * @param string  $subjek_pajak_id       ID subjek pajak (wajib pajak)
     * @param string  $kd_kanwil_bank        Kode kanwil bank
     * @param string  $kd_kppbb_bank         Kode KPPBB bank
     * @param string  $kd_bank_tunggal       Kode bank tunggal
     * @param string  $kd_bank_persepsi      Kode bank persepsi
     * @param string  $kd_bank_tp            Kode TP bank
     * @param int     $njoptkp               NJOP tidak kena pajak
     * @param int     $buku1_min             Rentang minimal Buku 1
     * @param int     $buku1_max             Rentang maksimal Buku 1
     * ... (sama untuk buku2–buku5)
     * @param string  $jns_bumi              Jenis bumi
     * @param string  $thn_pajak_sppt        Tahun pajak SPPT
     * @param Carbon  $tgl_jatuh_tempo_sppt  Tanggal jatuh tempo pembayaran
     * @param Carbon  $tgl_terbit_sppt       Tanggal terbit SPPT
     * @param string  $nip_pencetak_sppt     NIP petugas yang mencetak
     * @param Carbon  $tgl_cetak_sppt        Tanggal pencetakan SPPT
     * @param int     $pbb_minimal           Nilai PBB minimal
     */

    public static function call(
        $kd_propinsi,
        $kd_dati2,
        $kd_kecamatan,
        $kd_kelurahan,
        $kd_blok,
        $no_urut,
        $kd_jns_op,
        $no_persil,
        $njop_bumi,
        $njop_bng,
        $luas_bumi,
        $luas_bng,
        $subjek_pajak_id,
        $kd_kanwil_bank,
        $kd_kppbb_bank,
        $kd_bank_tunggal,
        $kd_bank_persepsi,
        $kd_bank_tp,
        $njoptkp,
        $buku1_min,
        $buku1_max,
        $buku2_min,
        $buku2_max,
        $buku3_min,
        $buku3_max,
        $buku4_min,
        $buku4_max,
        $buku5_min,
        $buku5_max,
        $jns_bumi,
        $thn_pajak_sppt,
        Carbon $tgl_jatuh_tempo_sppt,
        Carbon $tgl_terbit_sppt,
        $nip_pencetak_sppt,
        Carbon $tgl_cetak_sppt,
        $pbb_minimal
    ) {
        // Ubah objek Carbon menjadi string format tanggal Oracle (YYYY-MM-DD HH24:MI:SS)
        $tglJatuhTempo = self::formatOracleDate($tgl_jatuh_tempo_sppt);
        $tglTerbit     = self::formatOracleDate($tgl_terbit_sppt);
        $tglCetak      = self::formatOracleDate($tgl_cetak_sppt);

        // Ambil koneksi PDO ke database Oracle
        $pdo = DB::connection('oracle')->getPdo();

        // Siapkan perintah PL/SQL untuk memanggil prosedur Oracle
        $sql = "BEGIN PENETAPAN_MASSAL(
            :s_prop, :s_dat, :s_kec, :s_kel, :s_blk, :s_urut, :s_jns, :s_persil,
            :i_njop_bumi, :i_njop_bng, :i_luas_bumi, :i_luas_bng,
            :s_wp_id,
            :s_kd_kanwil, :s_kd_kppbb, :s_kd_tunggal, :s_kd_persepsi, :s_kd_tp,
            :i_njoptkp,
            :i_min_b1, :i_max_b1,
            :i_min_b2, :i_max_b2,
            :i_min_b3, :i_max_b3,
            :i_min_b4, :i_max_b4,
            :i_min_b5, :i_max_b5,
            :s_jpt, :s_thn,
            :d_tjtt_bk1, :d_tjtt_bk2, :d_tjtt_bk3, :d_tjtt_bk4, :d_tjtt_bk5,
            :d_trb_bk1, :d_trb_bk2, :d_trb_bk3, :d_trb_bk4, :d_trb_bk5,
            :s_nip, :d_nip_tgl,
            :i_pbb_min
        ); END;";

        $stmt = $pdo->prepare($sql);

        // === Binding Parameter Input ===
        // Bagian alamat & identifikasi objek
        $stmt->bindValue(':s_prop', $kd_propinsi, \PDO::PARAM_STR);
        $stmt->bindValue(':s_dat', $kd_dati2, \PDO::PARAM_STR);
        $stmt->bindValue(':s_kec', $kd_kecamatan, \PDO::PARAM_STR);
        $stmt->bindValue(':s_kel', $kd_kelurahan, \PDO::PARAM_STR);
        $stmt->bindValue(':s_blk', $kd_blok, \PDO::PARAM_STR);
        $stmt->bindValue(':s_urut', $no_urut, \PDO::PARAM_STR);
        $stmt->bindValue(':s_jns', $kd_jns_op, \PDO::PARAM_STR);
        $stmt->bindValue(':s_persil', $no_persil, \PDO::PARAM_STR);

        // Nilai & luas NJOP
        $stmt->bindValue(':i_njop_bumi', $njop_bumi, \PDO::PARAM_INT);
        $stmt->bindValue(':i_njop_bng', $njop_bng, \PDO::PARAM_INT);
        $stmt->bindValue(':i_luas_bumi', $luas_bumi, \PDO::PARAM_INT);
        $stmt->bindValue(':i_luas_bng', $luas_bng, \PDO::PARAM_INT);

        // Subjek pajak (wajib pajak)
        $stmt->bindValue(':s_wp_id', $subjek_pajak_id, \PDO::PARAM_STR);

        // Kode bank & administrasi
        $stmt->bindValue(':s_kd_kanwil', $kd_kanwil_bank, \PDO::PARAM_STR);
        $stmt->bindValue(':s_kd_kppbb', $kd_kppbb_bank, \PDO::PARAM_STR);
        $stmt->bindValue(':s_kd_tunggal', $kd_bank_tunggal, \PDO::PARAM_STR);
        $stmt->bindValue(':s_kd_persepsi', $kd_bank_persepsi, \PDO::PARAM_STR);
        $stmt->bindValue(':s_kd_tp', $kd_bank_tp, \PDO::PARAM_STR);

        // NJOP tidak kena pajak
        $stmt->bindValue(':i_njoptkp', $njoptkp, \PDO::PARAM_INT);

        // Rentang nilai per buku (Buku 1–5)
        $stmt->bindValue(':i_min_b1', $buku1_min, \PDO::PARAM_INT);
        $stmt->bindValue(':i_max_b1', $buku1_max, \PDO::PARAM_INT);
        $stmt->bindValue(':i_min_b2', $buku2_min, \PDO::PARAM_INT);
        $stmt->bindValue(':i_max_b2', $buku2_max, \PDO::PARAM_INT);
        $stmt->bindValue(':i_min_b3', $buku3_min, \PDO::PARAM_INT);
        $stmt->bindValue(':i_max_b3', $buku3_max, \PDO::PARAM_INT);
        $stmt->bindValue(':i_min_b4', $buku4_min, \PDO::PARAM_INT);
        $stmt->bindValue(':i_max_b4', $buku4_max, \PDO::PARAM_INT);
        $stmt->bindValue(':i_min_b5', $buku5_min, \PDO::PARAM_INT);
        $stmt->bindValue(':i_max_b5', $buku5_max, \PDO::PARAM_INT);

        // Jenis bumi & tahun pajak
        $stmt->bindValue(':s_jpt', $jns_bumi, \PDO::PARAM_STR);
        $stmt->bindValue(':s_thn', $thn_pajak_sppt, \PDO::PARAM_STR);

        // Tanggal jatuh tempo (sama untuk semua buku 1–5)
        $stmt->bindValue(':d_tjtt_bk1', $tglJatuhTempo);
        $stmt->bindValue(':d_tjtt_bk2', $tglJatuhTempo);
        $stmt->bindValue(':d_tjtt_bk3', $tglJatuhTempo);
        $stmt->bindValue(':d_tjtt_bk4', $tglJatuhTempo);
        $stmt->bindValue(':d_tjtt_bk5', $tglJatuhTempo);

        // Tanggal terbit (sama untuk semua buku 1–5)
        $stmt->bindValue(':d_trb_bk1', $tglTerbit);
        $stmt->bindValue(':d_trb_bk2', $tglTerbit);
        $stmt->bindValue(':d_trb_bk3', $tglTerbit);
        $stmt->bindValue(':d_trb_bk4', $tglTerbit);
        $stmt->bindValue(':d_trb_bk5', $tglTerbit);

        // Data pencetakan
        $stmt->bindValue(':s_nip', $nip_pencetak_sppt, \PDO::PARAM_STR);
        $stmt->bindValue(':d_nip_tgl', $tglCetak);
        $stmt->bindValue(':i_pbb_min', $pbb_minimal, \PDO::PARAM_INT);

        // Jalankan prosedur
        $stmt->execute();
    }

    /**
     * Bantu ubah objek Carbon menjadi string tanggal yang kompatibel dengan Oracle.
     */
    private static function formatOracleDate(Carbon $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
