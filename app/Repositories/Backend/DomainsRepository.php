<?php

namespace App\Repositories\Backend;

use App\Events\Backend\Domains\DomainCreated;
use App\Events\Backend\Domains\DomainDeleted;
use App\Events\Backend\Domains\DomainUpdated;
use App\Exceptions\GeneralException;
use App\Models\Domain;
use App\Repositories\BaseRepository;

class DomainsRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Domain::class;

    /**
     * Sortable.
     *
     * @var array
     */
    private $sortable = [
        'id',
        'name',
        'url',
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
                'url',
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

        if ($domain = Domain::create($input)) {
            event(new DomainCreated($domain));

            return $domain;
        }

        throw new GeneralException(__('exceptions.backend.domains.create_error'));
    }

    /**
     * @param \App\Models\Domain $domain
     * @param array $input
     */
    public function update(Domain $domain, array $input)
    {
        $input['updated_by'] = auth()->user()->id;
        $input['status'] = $input['status'] ?? 0;

        if ($domain->update($input)) {
            event(new DomainUpdated($domain));

            return $domain->fresh();
        }

        throw new GeneralException(__('exceptions.backend.domains.update_error'));
    }

    /**
     * @param \App\Models\Domain $domain
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Domain $domain)
    {
        if ($domain->delete()) {
            event(new DomainDeleted($domain));

            return true;
        }

        throw new GeneralException(__('exceptions.backend.domains.delete_error'));
    }
}
