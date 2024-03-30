<?php

namespace App\Events\Backend\Groups;

use Illuminate\Queue\SerializesModels;

/**
 * Class GroupUpdated.
 */
class GroupUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $group;

    /**
     * @param $page
     */
    public function __construct($group)
    {
        $this->group = $group;
    }
}
