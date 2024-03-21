<?php

namespace App\Http\Controllers\Backend\RedirectLinks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RedirectLinks\ManageRedirectLinksRequest;
use App\Repositories\Backend\RedirectLinksRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class RedirectLinksTableController extends Controller
{
    /**
     * @var \App\Repositories\Backend\RedirectLinksRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\RedirectLinksRepository $redirectLinks
     */
    public function __construct(RedirectLinksRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \App\Http\Requests\Backend\RedirectLinks\ManageRedirectLinksRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageRedirectLinksRequest $request)
    {
        return Datatables::of($this->repository->getForDataTable())
            ->escapeColumns(['domain'])
            ->editColumn('status', function ($redirectLinks) {
                return $redirectLinks->status_label;
            })
            ->editColumn('created_at', function ($redirectLinks) {
                return Carbon::parse($redirectLinks->created_at)->toDateString();
            })
            ->addColumn('actions', function ($redirectLinks) {
                return $redirectLinks->action_buttons;
            })
            ->make(true);
    }
}
