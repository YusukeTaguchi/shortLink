<?php

namespace App\Http\Controllers\Backend\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Groups\ManageGroupsRequest;
use App\Repositories\Backend\GroupsRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class GroupsTableController extends Controller
{
    /**
     * @var \App\Repositories\Backend\GroupsRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\GroupsRepository $groups
     */
    public function __construct(GroupsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \App\Http\Requests\Backend\Groups\ManageGroupsRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageGroupsRequest $request)
    {
        return Datatables::of($this->repository->getForDataTable())
            ->escapeColumns(['domain'])
            ->editColumn('status', function ($groups) {
                return $groups->status_label;
            })
            ->editColumn('created_at', function ($groups) {
                return Carbon::parse($groups->created_at)->toDateString();
            })
            ->addColumn('actions', function ($groups) {
                return $groups->action_buttons;
            })
            ->make(true);
    }
}
