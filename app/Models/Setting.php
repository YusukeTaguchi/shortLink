<?php

namespace App\Models;

use App\Models\Traits\ModelAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends BaseModel
{
    use ModelAttributes, SoftDeletes;

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'auto_redirect_type',
        'auto_redirect_to',
        'created_by',
        'updated_by',
    ];

    /**
     * Dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
