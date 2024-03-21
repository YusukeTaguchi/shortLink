<?php

namespace App\Events\Backend\RedirectLinks;

use Illuminate\Queue\SerializesModels;

/**
 * Class RedirectLinkDeleted.
 */
class RedirectLinkDeleted
{
    use SerializesModels;

    /**
     * @var
     */
    public $redirectLink;

    /**
     * @param $redirectLink
     */
    public function __construct($redirectLink)
    {
        $this->redirectLink = $redirectLink;
    }
}
