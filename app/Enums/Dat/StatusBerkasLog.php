<?php

namespace App\Enums\Dat;

enum StatusBerkasLog: string
{
    case BELUM_LENGKAP = 'BELUM LENGKAP';
    case KONSEP = 'KONSEP';
    case MENUNGGU_PENELITIAN = 'MENUNGGU PENELITIAN';
    case DALAM_PENELITIAN = 'DALAM PENELITIAN';
    case KOREKSI = 'KOREKSI';
    case MENUNGGU_VERIFIKASI = 'MENUNGGU VERIFIKASI';
    case BELUM_BAYAR = 'BELUM BAYAR';
    case MENUNGGU_PEMERIKSAAN = 'MENUNGGU PEMERIKSAAN';
    case PEMERIKSAAN = 'PEMERIKSAAN';
    case KURANG_BAYAR = 'KURANG BAYAR';
    case MENUNGGU_TTE = 'MENUNGGU TTE';
    case SELESAI = 'SELESAI';

    public function label(): string
    {
        return match ($this) {
            self::BELUM_LENGKAP => 'BELUM LENGKAP',
            self::KONSEP => 'KONSEP',
            self::MENUNGGU_PENELITIAN => 'MENUNGGU PENELITIAN',
            self::DALAM_PENELITIAN => 'DALAM PENELITIAN',
            self::KOREKSI => 'KOREKSI',
            self::MENUNGGU_VERIFIKASI => 'MENUNGGU VERIFIKASI',
            self::BELUM_BAYAR => 'BELUM BAYAR',
            self::MENUNGGU_PEMERIKSAAN => 'MENUNGGU PEMERIKSAAN',
            self::PEMERIKSAAN => 'PEMERIKSAAN',
            self::KURANG_BAYAR => 'KURANG BAYAR',
            self::SELESAI => 'SELESAI',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'label' => $case->value,
            'value' => $case->value,
        ], self::cases());
    }
}
