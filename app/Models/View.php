<?php

namespace App\Models;

use App\Models\Traits\ModelAttributes;

class View extends BaseModel
{
    use ModelAttributes;

     /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'viewed',
        'date'
    ];

    /**
     * Dates.
     *
     * @var array
     */
    protected $dates = [
    ];
}
