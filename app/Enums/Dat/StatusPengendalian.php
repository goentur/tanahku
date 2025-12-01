<?php

namespace App\Enums\Dat;

enum StatusPengendalian: string
{
    case Belum = '0';
    case Sesuai = '1';
    case Pemeriksaan = '2';

    public static function text($value) : string
    {
        return match ($value) {
            self::Belum => 'Belum',
            self::Pemeriksaan => 'Pemeriksaan',
            self::Sesuai => 'Sesuai',
        };
    }

    public static function values(): array
    {
        return [
            self::Belum->value => self::text(self::Belum),
            self::Pemeriksaan->value => self::text(self::Pemeriksaan),
            self::Sesuai->value => self::text(self::Sesuai),
        ];
    }
}
