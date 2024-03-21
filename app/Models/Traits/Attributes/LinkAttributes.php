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
