<?php

namespace App\Events\Backend\RedirectLinks;

use Illuminate\Queue\SerializesModels;

/**
 * Class RedirectLinkCreated.
 */
class RedirectLinkCreated
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
