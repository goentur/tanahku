<?php

namespace App\Models\BPHTB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PPAT extends Model
{
    protected $table = 'BPHTB.PPAT';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public function alamatLengkap(): Attribute
    {
        return Attribute::get(
            fn() => (string)  str($this->alamat)
                ->append(' RT/RW ', $this->rt, '/', $this->rw)
                ->when(!blank($this->kelurahan), fn($str) => $str->append(', ', $this->kelurahan))
                ->when(!blank($this->kecamatan), fn($str) => $str->append(', ', $this->kecamatan))
                ->append(', ', $this->kota)
        );
    }
}
