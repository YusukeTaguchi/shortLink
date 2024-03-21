<?php

namespace App\Models\Traits\Attributes;

trait RedirectLinkAttributes
{
    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '<div class="btn-group" role="group" aria-label="'.trans('labels.backend.access.users.user_actions').'">'.
                $this->getEditButtonAttribute('edit-link', 'admin.redirect-links.edit').
                $this->getDeleteButtonAttribute('delete-link', 'admin.redirect-links.destroy').
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

}
