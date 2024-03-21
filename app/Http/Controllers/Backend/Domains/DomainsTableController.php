<?php

namespace App\Http\Controllers\Backend\Domains;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Domains\ManageDomainsRequest;
use App\Repositories\Backend\DomainsRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class DomainsTableController extends Controller
{
    /**
     * @var \App\Repositories\Backend\DomainsRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\DomainsRepository $domains
     */
    public function __construct(DomainsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \App\Http\Requests\Backend\Domains\ManageDomainsRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageDomainsRequest $request)
    {
        return Datatables::of($this->repository->getForDataTable())
            ->escapeColumns(['name'])
            ->filterColumn('status', function ($query, $keyword) {
                if (in_array(strtolower($keyword), ['active', 'inactive'])) {
                    $query->where('domains.status', (strtolower($keyword) == 'active') ? 1 : 0);
                }
            })
            ->editColumn('status', function ($domains) {
                return $domains->status_label;
            })
            ->editColumn('created_at', function ($domains) {
                return Carbon::parse($domains->created_at)->toDateString();
            })
            ->addColumn('actions', function ($domains) {
                return $domains->action_buttons;
            })
            ->make(true);
    }
}
