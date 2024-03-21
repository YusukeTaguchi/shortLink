<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Settings\ManageSettingsRequest;
use App\Repositories\Backend\SettingsRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class SettingsTableController extends Controller
{
    /**
     * @var \App\Repositories\Backend\SettingsRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\SettingsRepository $settings
     */
    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \App\Http\Requests\Backend\Settings\ManageSettingsRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageSettingsRequest $request)
    {
        return Datatables::of($this->repository->getForDataTable())
            ->escapeColumns(['auto_redirect_type'])
            ->editColumn('created_at', function ($settings) {
                return Carbon::parse($settings->created_at)->toDateString();
            })
            ->addColumn('actions', function ($settings) {
                return $settings->action_buttons;
            })
            ->make(true);
    }
}
