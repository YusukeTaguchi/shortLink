<?php

namespace App\Http\Controllers\Backend\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Groups\CreateGroupsRequest;
use App\Http\Requests\Backend\Groups\DeleteGroupsRequest;
use App\Http\Requests\Backend\Groups\ManageGroupsRequest;
use App\Http\Requests\Backend\Groups\StoreGroupsRequest;
use App\Http\Requests\Backend\Groups\UpdateGroupsRequest;
use App\Http\Responses\GroupResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Group;
use App\Repositories\Backend\GroupsRepository;
use Illuminate\Support\Facades\View;

class GroupsController extends Controller
{
    /**
     * @var \App\Repositories\Backend\GroupsRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\GroupsRepository $group
     */
    public function __construct(GroupsRepository $repository)
    {
        $this->repository = $repository;
        View::share('js', ['groups']);
    }

    /**
     * @param \App\Http\Requests\Backend\Groups\ManageGroupsRequest $request
     *
     * @return ViewResponse
     */
    public function index(ManageGroupsRequest $request)
    {
        return new ViewResponse('backend.groups.index');
    }

    /**
     * @param \App\Http\Requests\Backend\Groups\CreateGroupsRequest $request
     *
     * @return ViewResponse
     */
    public function create(CreateGroupsRequest $request)
    {
        return new ViewResponse('backend.groups.create');
    }

    /**
     * @param \App\Http\Requests\Backend\Groups\StoreGroupsRequest $request
     *
     * @return \App\Http\Responses\GroupResponse
     */
    public function store(StoreGroupsRequest $request)
    {
        $this->repository->create($request->except('_token'));

        return new GroupResponse(route('admin.groups.index'), ['flash_success' => __('alerts.backend.groups.created')]);
    }

    /**
     * @param \App\Models\Group $group
     * @param \App\Http\Requests\Backend\Groups\ManagePageRequest $request
     *
     * @return ViewResponse
     */
    public function edit(Group $group, ManageGroupsRequest $request)
    {
        return new ViewResponse('backend.groups.edit', ['group' => $group]);
    }

    /**
     * @param \App\Models\Group $group
     * @param \App\Http\Requests\Backend\Groups\UpdateGroupsRequest $request
     *
     * @return \App\Http\Responses\GroupResponse
     */
    public function update(Group $group, UpdateGroupsRequest $request)
    {
        $this->repository->update($group, $request->except(['_token', '_method']));

        return new GroupResponse(route('admin.groups.index'), ['flash_success' => __('alerts.backend.groups.updated')]);
    }

    /**
     * @param \App\Models\Group $group
     * @param \App\Http\Requests\Backend\Pages\DeleteGroupRequest $request
     *
     * @return \App\Http\Responses\GroupResponse
     */
    public function destroy(Group $group, DeleteGroupsRequest $request)
    {
        $this->repository->delete($group);

        return new GroupResponse(route('admin.groups.index'), ['flash_success' => __('alerts.backend.groups.deleted')]);
    }
}
