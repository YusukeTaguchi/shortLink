<?php

namespace App\Repositories\Backend;

use App\Events\Backend\Groups\GroupCreated;
use App\Events\Backend\Groups\GroupDeleted;
use App\Events\Backend\Groups\GroupUpdated;
use App\Exceptions\GeneralException;
use App\Models\Group;
use App\Repositories\BaseRepository;

class GroupsRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Group::class;

    /**
     * Sortable.
     *
     * @var array
     */
    private $sortable = [
        'id',
        'name',
        'status',
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
                'name',
                'created_at',
                'status',
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
        $input['status'] = $input['status'] ?? 0;

        if ($group = Group::create($input)) {
            event(new GroupCreated($group));

            return $group;
        }

        throw new GeneralException(__('exceptions.backend.groups.create_error'));
    }

    /**
     * @param \App\Models\Group $group
     * @param array $input
     */
    public function update(Group $group, array $input)
    {
        $input['updated_by'] = auth()->user()->id;
        $input['status'] = $input['status'] ?? 0;

        if ($group->update($input)) {
            event(new GroupUpdated($group));

            return $group->fresh();
        }

        throw new GeneralException(__('exceptions.backend.groups.update_error'));
    }

    /**
     * @param \App\Models\Group $group
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Group $group)
    {
        if ($group->delete()) {
            event(new GroupDeleted($group));

            return true;
        }

        throw new GeneralException(__('exceptions.backend.groups.delete_error'));
    }
}
