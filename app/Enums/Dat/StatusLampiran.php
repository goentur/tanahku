<?php

namespace App\Enums\Dat;

enum StatusLampiran: string
{
    case Belum   = '0';
    case Selesai = '1';
    case Tolak   = '2';
    case Koreksi = '3';
    case Proses  = '4';
    case Lengkap = '5';

    public static function text($value) : string
    {
        return match ($value) {
            self::Belum => 'Belum',
            self::Tolak => 'DiTolak',
            self::Selesai => 'DiTeliti',
            self::Koreksi => 'Koreksi',
            self::Proses => 'Proses',
            self::Lengkap => 'Lengkap',
        };
    }

    public static function values(): array
    {
        return [
            self::Belum->value => self::text(self::Belum),
            self::Tolak->value => self::text(self::Tolak),
            self::Selesai->value => self::text(self::Selesai),
            self::Koreksi->value => self::text(self::Koreksi),
            self::Proses->value => self::text(self::Proses),
            self::Lengkap->value => self::text(self::Lengkap),
        ];
    }
}
