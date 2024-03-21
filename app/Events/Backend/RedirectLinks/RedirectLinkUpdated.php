<?php

namespace App\Events\Backend\RedirectLinks;

use Illuminate\Queue\SerializesModels;

/**
 * Class RedirectLinkUpdated.
 */
class RedirectLinkUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $redirectLink;

    /**
     * @param $page
     */
    public function __construct($redirectLink)
    {
        $this->redirectLink = $redirectLink;
    }
}
