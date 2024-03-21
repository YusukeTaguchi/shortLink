<?php

namespace App\Events\Backend\Links;

use Illuminate\Queue\SerializesModels;

/**
 * Class LinkUpdated.
 */
class LinkUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $links;

    /**
     * @param $links
     */
    public function __construct($links)
    {
        $this->links = $links;
    }
}
