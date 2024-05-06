<?php

namespace App\Models;

use App\Models\Traits\ModelAttributes;

class ViewsCountsByHour extends BaseModel
{
    use ModelAttributes;
    
    protected $table = 'views_counts_by_hour';

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
        'link_id',
        'viewed',
        'date',
        'day'
    ];

    /**
     * Dates.
     *
     * @var array
     */
    protected $dates = [
    ];
}
