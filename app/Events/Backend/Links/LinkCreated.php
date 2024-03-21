<?php

namespace App\Events\Backend\Links;

use Illuminate\Queue\SerializesModels;

/**
 * Class LinkCreated.
 */
class LinkCreated
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
