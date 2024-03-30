<?php

namespace App\Events\Backend\Groups;

use Illuminate\Queue\SerializesModels;

/**
 * Class GroupDeleted.
 */
class GroupDeleted
{
    use SerializesModels;

    /**
     * @var
     */
    public $group;

    /**
     * @param $group
     */
    public function __construct($group)
    {
        $this->group = $group;
    }
}
