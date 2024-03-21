<?php

namespace App\Events\Backend\Domains;

use Illuminate\Queue\SerializesModels;

/**
 * Class DomainCreated.
 */
class DomainCreated
{
    use SerializesModels;

    /**
     * @var
     */
    public $domain;

    /**
     * @param $page
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }
}
