<?php

namespace App\Models;

use App\Models\Traits\ModelAttributes;

class ViewsCountsByMonth extends BaseModel
{
    use ModelAttributes;

    protected $table = 'views_counts_by_month';

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
        'year',
        'month'
    ];

    /**
     * Dates.
     *
     * @var array
     */
    protected $dates = [
    ];
}
