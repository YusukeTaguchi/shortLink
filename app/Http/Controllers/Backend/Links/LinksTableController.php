<?php

namespace App\Http\Controllers\Backend\Links;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Links\ManageLinksRequest;
use App\Repositories\Backend\LinksRepository;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class LinksTableController.
 */
class LinksTableController extends Controller
{
    /**
     * @var \App\Repositories\Backend\LinksRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\LinksRepository $repository
     */
    public function __construct(LinksRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \App\Http\Requests\Backend\Links\ManageLinksRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageLinksRequest $request)
    {
        
        return Datatables::of($this->repository->getForDataTable())
            ->escapeColumns(['title'])
            ->addColumn('status', function ($links) {
                return $links->status;
            })
            ->addColumn('created_by', function ($links) {
                return $links->user_name;
            })
            ->addColumn('created_at', function ($links) {
                return $links->created_at->toDateString();
            })
            ->addColumn('actions', function ($links) {
                return $links->action_buttons;
            })
            ->make(true);
    }
}
