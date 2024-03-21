<?php

namespace App\Http\Controllers\Backend\RedirectLinks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RedirectLinks\CreateRedirectLinksRequest;
use App\Http\Requests\Backend\RedirectLinks\DeleteRedirectLinksRequest;
use App\Http\Requests\Backend\RedirectLinks\ManageRedirectLinksRequest;
use App\Http\Requests\Backend\RedirectLinks\StoreRedirectLinksRequest;
use App\Http\Requests\Backend\RedirectLinks\UpdateRedirectLinksRequest;
use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\RedirectLink;
use App\Repositories\Backend\RedirectLinksRepository;
use Illuminate\Support\Facades\View;

class RedirectLinksController extends Controller
{
    /**
     * @var \App\Repositories\Backend\RedirectLinksRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\RedirectLinksRepository $redirectLink
     */
    public function __construct(RedirectLinksRepository $repository)
    {
        $this->repository = $repository;
        View::share('js', ['redirect-links']);
    }

    /**
     * @param \App\Http\Requests\Backend\RedirectLinks\ManageRedirectLinksRequest $request
     *
     * @return ViewResponse
     */
    public function index(ManageRedirectLinksRequest $request)
    {
        return new ViewResponse('backend.redirect-links.index');
    }

    /**
     * @param \App\Http\Requests\Backend\RedirectLinks\CreateRedirectLinksRequest $request
     *
     * @return ViewResponse
     */
    public function create(CreateRedirectLinksRequest $request)
    {
        return new ViewResponse('backend.redirect-links.create');
    }

    /**
     * @param \App\Http\Requests\Backend\RedirectLinks\StoreRedirectLinksRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function store(StoreRedirectLinksRequest $request)
    {
        $this->repository->create($request->except('_token'));

        return new RedirectResponse(route('admin.redirect-links.index'), ['flash_success' => __('alerts.backend.redirect-links.created')]);
    }

    /**
     * @param \App\Models\RedirectLink $redirectLink
     * @param \App\Http\Requests\Backend\RedirectLinks\ManagePageRequest $request
     *
     * @return ViewResponse
     */
    public function edit(RedirectLink $redirectLink, ManageRedirectLinksRequest $request)
    {
        return new ViewResponse('backend.redirect-links.edit', ['redirectLink' => $redirectLink]);
    }

    /**
     * @param \App\Models\RedirectLink $redirectLink
     * @param \App\Http\Requests\Backend\RedirectLinks\UpdateRedirectLinksRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function update(RedirectLink $redirectLink, UpdateRedirectLinksRequest $request)
    {
        $this->repository->update($redirectLink, $request->except(['_token', '_method']));

        return new RedirectResponse(route('admin.redirect-links.index'), ['flash_success' => __('alerts.backend.redirect-links.updated')]);
    }

    /**
     * @param \App\Models\RedirectLink $redirectLink
     * @param \App\Http\Requests\Backend\Pages\DeleteRedirectLinkRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function destroy(RedirectLink $redirectLink, DeleteRedirectLinksRequest $request)
    {
        $this->repository->delete($redirectLink);

        return new RedirectResponse(route('admin.redirect-links.index'), ['flash_success' => __('alerts.backend.redirect-links.deleted')]);
    }
}
