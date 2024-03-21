<?php

namespace App\Events\Backend\Settings;

use Illuminate\Queue\SerializesModels;

/**
 * Class SettingDeleted.
 */
class SettingDeleted
{
    use SerializesModels;

    /**
     * @var
     */
    public $setting;

    /**
     * @param $setting
     */
    public function __construct($setting)
    {
        $this->setting = $setting;
    }
}
