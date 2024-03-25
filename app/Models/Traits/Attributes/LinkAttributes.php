<?php

namespace App\Models\Traits\Attributes;

trait LinkAttributes
{
    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '<div class="btn-group" role="group" aria-label="'.trans('labels.backend.access.users.user_actions').'">'.
                $this->getEditButtonAttribute('edit-link', 'admin.links.edit').
                $this->getSyncButtonAttribute('edit-link', 'admin.links.sync').
                $this->getDeleteButtonAttribute('delete-link', 'admin.links.destroy').
                '</div>';
    }

    /**
     * Get Display Status Attribute.
     *
     * @var string
     */
    public function getDisplayStatusAttribute(): string
    {
        return $this->statuses[$this->status] ?? null;
    }



    /**
     * @return string
     */
    public function getSyncButtonAttribute($permission, $route)
    {
        if (access()->allow($permission)) {
            return '<a href="'.route($route, $this).'" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.sync').'" class="btn btn-primary btn-sm mr-1">
                        <i class="fas fa-sync"></i>
                    </a>';
        }
    }

    /**
     * Get Statuses Attribute.
     *
     * @var string
     */
    public function getStatusesAttribute(): array
    {
        return $this->statuses;
    }

      /**
     * Get Display Fakes Attribute.
     *
     * @var string
     */
    public function getDisplayFakesAttribute()
    {
        return $this->fakes[$this->fake] ?? null;
    }

    /**
     * Get Statuses Attribute.
     *
     * @var string
     */
    public function getFakesAttribute(): array
    {
        return $this->fakes;
    }
}
