<?php

namespace App\Enums\Dat;

enum StatusVerifikasiWp: string
{
    case Belum   = '0';
    case Selesai = '1';
    case Tolak   = '2';
    case Koreksi = '3';
    case Proses  = '4';


    public static function text($value) : string
    {
        return match ($value) {
            self::Belum => 'Belum',
            self::Proses => 'Proses',
            self::Tolak => 'Tolak',
            self::Koreksi => 'Koreksi',
            self::Selesai => 'Terverifikasi',
        };
    }

    public static function values(): array
    {
        return [
            self::Belum->value => self::text(self::Belum),
            self::Proses->value => self::text(self::Proses),
            self::Tolak->value => self::text(self::Tolak),
            self::Koreksi->value => self::text(self::Koreksi),
            self::Selesai->value => self::text(self::Selesai),
        ];
    }
}
