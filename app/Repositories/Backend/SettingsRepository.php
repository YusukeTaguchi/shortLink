<?php

namespace App\Repositories\Backend;

use App\Events\Backend\Settings\SettingCreated;
use App\Events\Backend\Settings\SettingDeleted;
use App\Events\Backend\Settings\SettingUpdated;
use App\Exceptions\GeneralException;
use App\Models\Setting;
use App\Repositories\BaseRepository;

class SettingsRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Setting::class;

    /**
     * Sortable.
     *
     * @var array
     */
    private $sortable = [
        'id',
        'auto_redirect_type',
        'auto_redirect_to',
        'created_at',
        'updated_at',
    ];

    /**
     * Retrieve List.
     *
     * @var array
     * @return Collection
     */
    public function retrieveList(array $options = [])
    {
        $perPage = isset($options['per_page']) ? (int) $options['per_page'] : 20;
        $orderBy = isset($options['order_by']) && in_array($options['order_by'], $this->sortable) ? $options['order_by'] : 'created_at';
        $order = isset($options['order']) && in_array($options['order'], ['asc', 'desc']) ? $options['order'] : 'desc';
        $query = $this->query()
            ->orderBy($orderBy, $order);

        if ($perPage == -1) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()
            ->select([
                'id',
                'auto_redirect_type',
                'auto_redirect_to',
                'created_at'
            ]);
    }

    /**
     * @param array $input
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return bool
     */
    public function create(array $input)
    {
        $input['created_by'] = auth()->user()->id;

        if ($setting = Setting::create($input)) {
            event(new SettingCreated($setting));

            return $setting;
        }

        throw new GeneralException(__('exceptions.backend.settings.create_error'));
    }

    /**
     * @param \App\Models\Setting $setting
     * @param array $input
     */
    public function update(Setting $setting, array $input)
    {
        $input['updated_by'] = auth()->user()->id;

        if ($setting->update($input)) {
            event(new SettingUpdated($setting));

            return $setting->fresh();
        }

        throw new GeneralException(__('exceptions.backend.settings.update_error'));
    }

    /**
     * @param \App\Models\Setting $setting
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Setting $setting)
    {
        if ($setting->delete()) {
            event(new SettingDeleted($setting));

            return true;
        }

        throw new GeneralException(__('exceptions.backend.settings.delete_error'));
    }
}
