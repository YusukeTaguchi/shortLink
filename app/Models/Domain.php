<?php

namespace App\Models;

use App\Models\Traits\Attributes\DomainAttributes;
use App\Models\Traits\ModelAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends BaseModel
{
    use ModelAttributes, SoftDeletes, DomainAttributes;

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'status',
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

    /**
     * Statuses.
     *
     * @var array
     */
    protected $statuses = [
        0 => 'InActive',
        1 => 'Published',
        2 => 'Draft'
    ];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = [
        'display_status',
    ];
}
