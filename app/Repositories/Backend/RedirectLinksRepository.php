<?php

namespace App\Repositories\Backend;

use App\Events\Backend\RedirectLinks\RedirectLinkCreated;
use App\Events\Backend\RedirectLinks\RedirectLinkDeleted;
use App\Events\Backend\RedirectLinks\RedirectLinkUpdated;
use App\Exceptions\GeneralException;
use App\Models\RedirectLink;
use App\Repositories\BaseRepository;

class RedirectLinksRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = RedirectLink::class;

    /**
     * Sortable.
     *
     * @var array
     */
    private $sortable = [
        'id',
        'domain',
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
                'domain',
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

        if ($redirectLink = RedirectLink::create($input)) {
            event(new RedirectLinkCreated($redirectLink));

            return $redirectLink;
        }

        throw new GeneralException(__('exceptions.backend.redirect-links.create_error'));
    }

    /**
     * @param \App\Models\RedirectLink $redirectLink
     * @param array $input
     */
    public function update(RedirectLink $redirectLink, array $input)
    {
        $input['updated_by'] = auth()->user()->id;
        $input['status'] = $input['status'] ?? 0;

        if ($redirectLink->update($input)) {
            event(new RedirectLinkUpdated($redirectLink));

            return $redirectLink->fresh();
        }

        throw new GeneralException(__('exceptions.backend.redirect-links.update_error'));
    }

    /**
     * @param \App\Models\RedirectLink $redirectLink
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(RedirectLink $redirectLink)
    {
        if ($redirectLink->delete()) {
            event(new RedirectLinkDeleted($redirectLink));

            return true;
        }

        throw new GeneralException(__('exceptions.backend.redirect-links.delete_error'));
    }
}
