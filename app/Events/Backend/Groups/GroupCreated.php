<?php

namespace App\Events\Backend\Groups;

use Illuminate\Queue\SerializesModels;

/**
 * Class GroupCreated.
 */
class GroupCreated
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
