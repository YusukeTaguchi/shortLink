<?php

namespace App\Http\Controllers\Backend\Domains;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Domains\CreateDomainsRequest;
use App\Http\Requests\Backend\Domains\DeleteDomainsRequest;
use App\Http\Requests\Backend\Domains\ManageDomainsRequest;
use App\Http\Requests\Backend\Domains\StoreDomainsRequest;
use App\Http\Requests\Backend\Domains\UpdateDomainsRequest;
use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Domain;
use App\Repositories\Backend\DomainsRepository;
use Illuminate\Support\Facades\View;

class DomainsController extends Controller
{
    /**
     * @var \App\Repositories\Backend\DomainsRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\DomainsRepository $domain
     */
    public function __construct(DomainsRepository $repository)
    {
        $this->repository = $repository;
        View::share('js', ['domains']);
    }

    /**
     * @param \App\Http\Requests\Backend\Domains\ManageDomainsRequest $request
     *
     * @return ViewResponse
     */
    public function index(ManageDomainsRequest $request)
    {
        return new ViewResponse('backend.domains.index');
    }

    /**
     * @param \App\Http\Requests\Backend\Domains\CreateDomainsRequest $request
     *
     * @return ViewResponse
     */
    public function create(CreateDomainsRequest $request)
    {
        return new ViewResponse('backend.domains.create');
    }

    /**
     * @param \App\Http\Requests\Backend\Domains\StoreDomainsRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function store(StoreDomainsRequest $request)
    {
        $this->repository->create($request->except('_token'));

        return new RedirectResponse(route('admin.domains.index'), ['flash_success' => __('alerts.backend.domains.created')]);
    }

    /**
     * @param \App\Models\Domain $domain
     * @param \App\Http\Requests\Backend\Domains\ManagePageRequest $request
     *
     * @return ViewResponse
     */
    public function edit(Domain $domain, ManageDomainsRequest $request)
    {
        return new ViewResponse('backend.domains.edit', ['domain' => $domain]);
    }

    /**
     * @param \App\Models\Domain $domain
     * @param \App\Http\Requests\Backend\Domains\UpdateDomainsRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function update(Domain $domain, UpdateDomainsRequest $request)
    {
        $this->repository->update($domain, $request->except(['_token', '_method']));

        return new RedirectResponse(route('admin.domains.index'), ['flash_success' => __('alerts.backend.domains.updated')]);
    }

    /**
     * @param \App\Models\Domain $domain
     * @param \App\Http\Requests\Backend\Pages\DeleteDomainRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function destroy(Domain $domain, DeleteDomainsRequest $request)
    {
        $this->repository->delete($domain);

        return new RedirectResponse(route('admin.domains.index'), ['flash_success' => __('alerts.backend.domains.deleted')]);
    }
}
