<?php

namespace App\Models;

use App\Models\Traits\ModelAttributes;

class ViewsCountsByDay extends BaseModel
{
    use ModelAttributes;
    
    protected $table = 'views_counts_by_day';

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
