<?php

namespace App\Events\Backend\Settings;

use Illuminate\Queue\SerializesModels;

/**
 * Class SettingCreated.
 */
class SettingCreated
{
    use SerializesModels;

    /**
     * @var
     */
    public $setting;

    /**
     * @param $page
     */
    public function __construct($setting)
    {
        $this->setting = $setting;
    }
}
