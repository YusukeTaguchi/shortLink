<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Settings\CreateSettingsRequest;
use App\Http\Requests\Backend\Settings\DeleteSettingsRequest;
use App\Http\Requests\Backend\Settings\ManageSettingsRequest;
use App\Http\Requests\Backend\Settings\StoreSettingsRequest;
use App\Http\Requests\Backend\Settings\UpdateSettingsRequest;
use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Setting;
use App\Repositories\Backend\SettingsRepository;
use Illuminate\Support\Facades\View;

class SettingsController extends Controller
{
    /**
     * @var \App\Repositories\Backend\SettingsRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\SettingsRepository $repository
     */
    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
        View::share('js', ['settings']);
    }

    /**
     * @param \App\Http\Requests\Backend\Settings\ManageSettingsRequest $request
     *
     * @return ViewResponse
     */
    public function index(ManageSettingsRequest $request)
    {
        return new ViewResponse('backend.settings.index');
    }

    /**
     * @param \App\Http\Requests\Backend\Settings\CreateSettingsRequest $request
     *
     * @return ViewResponse
     */
    public function create(CreateSettingsRequest $request)
    {
        return new ViewResponse('backend.settings.create');
    }

    /**
     * @param \App\Http\Requests\Backend\Settings\StoreSettingsRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function store(StoreSettingsRequest $request)
    {
        $this->repository->create($request->except('_token'));

        return new RedirectResponse(route('admin.settings.index'), ['flash_success' => __('alerts.backend.settings.created')]);
    }

    /**
     * @param \App\Models\Setting $setting
     * @param \App\Http\Requests\Backend\Settings\ManagePageRequest $request
     *
     * @return ViewResponse
     */
    public function edit(Setting $setting, ManageSettingsRequest $request)
    {
        return new ViewResponse('backend.settings.edit', ['setting' => $setting]);
    }

    /**
     * @param \App\Models\Setting $setting
     * @param \App\Http\Requests\Backend\Settings\UpdateSettingsRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function update(Setting $setting, UpdateSettingsRequest $request)
    {
        $this->repository->update($setting, $request->except(['_token', '_method']));

        return new RedirectResponse(route('admin.settings.index'), ['flash_success' => __('alerts.backend.settings.updated')]);
    }

    /**
     * @param \App\Models\Setting $setting
     * @param \App\Http\Requests\Backend\Pages\DeleteSettingRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function destroy(Setting $setting, DeleteSettingsRequest $request)
    {
        $this->repository->delete($setting);

        return new RedirectResponse(route('admin.settings.index'), ['flash_success' => __('alerts.backend.settings.deleted')]);
    }
}
