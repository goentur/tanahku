<?php

namespace App\Models\BPHTB;

use Illuminate\Database\Eloquent\Model;

class RefJph extends Model
{
    protected $table = 'BPHTB.REF_JPH';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;
}
