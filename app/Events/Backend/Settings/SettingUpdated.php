<?php

namespace App\Events\Backend\Settings;

use Illuminate\Queue\SerializesModels;

/**
 * Class SettingUpdated.
 */
class SettingUpdated
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
