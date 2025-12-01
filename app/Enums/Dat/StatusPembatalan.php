<?php

namespace App\Enums\Dat;

enum StatusPembatalan: string
{
    case Belum = '0';
    case Selesai = '1';
    case Tolak = '2';

    public static function text($value) : string
    {
        return match ($value) {
            self::Belum => 'Belum',
            self::Tolak => 'Tidak DiSetujui',
            self::Selesai => 'DiSetujui',
        };
    }

    public static function values(): array
    {
        return [
            self::Belum->value => self::text(self::Belum),
            self::Tolak->value => self::text(self::Tolak),
            self::Selesai->value => self::text(self::Selesai),
        ];
    }
}
