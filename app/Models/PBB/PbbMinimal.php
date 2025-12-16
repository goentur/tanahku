<?php

namespace App\Models\PBB;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class PbbMinimal extends Model
{
    use Compoships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'PBB.PBB_MINIMAL';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = ['kd_propinsi', 'kd_dati2', 'thn_pbb_minimal'];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    // const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    // const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];
}
