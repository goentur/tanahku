<?php

namespace App\Enums\Dat;

enum StatusBerkas: string
{
    case Konsep  = '0';
    case Selesai = '1';
    case Tolak   = '2';
    case Koreksi = '3';
    case Proses  = '4';
    case Baru    = '5';
    case Batal   = '6';

    public static function text($value) : string
    {
        return match ($value) {
            self::Konsep => 'KONSEP',
            self::Baru => 'BARU',
            self::Batal => 'BATAL',
            self::Proses => 'PROSES',
            self::Selesai => 'SELESAI',
            self::Koreksi => 'Koreksi',
            self::Tolak => 'Tolak',
        };
    }

    public static function values(): array
    {
        return [
            self::Konsep->value => self::text(self::Konsep),
            self::Baru->value => self::text(self::Baru),
            self::Batal->value => self::text(self::Batal),
            self::Proses->value => self::text(self::Proses),
            self::Selesai->value => self::text(self::Selesai),
            self::Koreksi->value => self::text(self::Koreksi),
            self::Tolak->value => self::text(self::Tolak),
        ];
    }
}
