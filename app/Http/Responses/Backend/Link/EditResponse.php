<?php

namespace App\Http\Responses\Backend\Link;

use Illuminate\Contracts\Support\Responsable;

class EditResponse implements Responsable
{
    protected $link;

    protected $domains;

    protected $status;

    public function __construct($link, $domains, $status)
    {
        $this->link = $link;
        $this->status = $status;
        $this->domains = $domains;
    }

    public function toResponse($request)
    {
        return view('backend.links.edit')->with([
            'link' => $this->link,
            'domains' => $this->domains,
            'status' => $this->status,
        ]);
    }
}
