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
            1 => 'JAN',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'APR',
            5 => 'MEI',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGU',
            9 => 'SEP',
            10 => 'OKT',
            11 => 'NOV',
            12 => 'DES',
        ];

        $hari = $date->format('d');
        $bulan = $bulanIndo[(int) $date->format('n')];
        $tahun = $date->format('Y');
        $waktu = $date->format('H:i:s');
        return "$hari $bulan $tahun $waktu";
    }
}
