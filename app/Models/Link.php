<?php

namespace App\Models;

use App\Models\Traits\Attributes\LinkAttributes;
use App\Models\Traits\ModelAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends BaseModel
{
    use ModelAttributes, SoftDeletes, LinkAttributes;

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'notes',
        'domain_id',
        'type_display',
        'fake',
        'original_link',
        'keywords',
        'description',
        'status',
        'clicked',
        'viewed',
        'thumbnail_image',
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
     * Fakes.
     *
     * @var array
     */
    protected $fakes = [
        0 => 'On',
        1 => 'Off'
    ];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = [
        'display_status',
        'display_fakes',
    ];


    // Define the relationship with Domain model
    public function domain()
     {
         return $this->belongsTo(Domain::class, 'domain_id');
     }
}
