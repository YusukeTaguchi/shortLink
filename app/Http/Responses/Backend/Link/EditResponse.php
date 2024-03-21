<?php

namespace App\Http\Responses\Backend\Link;

use Illuminate\Contracts\Support\Responsable;

class EditResponse implements Responsable
{
    protected $link;

    protected $status;

    public function __construct($link, $status)
    {
        $this->link = $link;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return view('backend.links.edit')->with([
            'link' => $this->link,
            'status' => $this->status,
        ]);
    }
}
