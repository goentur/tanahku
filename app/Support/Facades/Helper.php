<?php

namespace App\Support\Facades;

class Helper
{

    public static function ribuan($num, int $decimals = 0, string $decimal_separator = ',', string $thousands_separator = '.'): string
    {
        return number_format($num, $decimals, $decimal_separator, $thousands_separator);
    }
    public static function formatTanggalIndo($tanggal)
    {
        if (!$tanggal) {
            return '';
        }

        // Pastikan input adalah objek Carbon atau string tanggal yang valid
        $date = \Carbon\Carbon::parse($tanggal);

        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $hari = $date->format('d');
        $bulan = $bulanIndo[(int) $date->format('n')];
        $tahun = $date->format('Y');
        $waktu = $date->format('H:i:s');

        return "$hari $bulan $tahun $waktu";
    }
}
