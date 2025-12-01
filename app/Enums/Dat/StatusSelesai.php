<?php

namespace App\Enums\Dat;

enum StatusSelesai: string
{
    case Belum   = '0';
    case Selesai = '1';
    case Tolak   = '2';
    case Tunda   = '3';
    case Batal   = '4';

    public static function text($value) : string
    {
        return match ($value) {
            self::Belum => 'Belum',
            self::Tolak => 'Gagal',
            self::Batal => 'Batal',
            self::Selesai => 'Selesai',
            self::Tunda => 'Tunda',
        };
    }

    public static function values(): array
    {
        return [
            self::Belum->value => self::text(self::Belum),
            self::Tolak->value => self::text(self::Tolak),
            self::Batal->value => self::text(self::Batal),
            self::Selesai->value => self::text(self::Selesai),
            self::Tunda->value => self::text(self::Tunda),
        ];
    }
}
