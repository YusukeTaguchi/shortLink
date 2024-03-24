<?php

namespace App\Repositories\Backend;

use App\Events\Backend\Links\LinkCreated;
use App\Events\Backend\Links\LinkDeleted;
use App\Events\Backend\Links\LinkUpdated;
use App\Exceptions\GeneralException;
use App\Models\Link;
use App\Models\Domain;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LinksRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Link::class;

    protected $upload_path;

    /**
     * Sortable.
     *
     * @var array
     */
    private $sortable = [
        'id',
        'title',
        'slug',
        'notes',
        'keywords',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Storage Class Object.
     *
     * @var \Illuminate\Support\Facades\Storage
     */
    protected $storage;

    public function __construct()
    {
        $this->upload_path = 'img'.DIRECTORY_SEPARATOR.'link'.DIRECTORY_SEPARATOR;
        $this->storage = Storage::disk('public');
    }

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
            ->with([
                'owner',
                'updater',
            ])
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
            ->leftjoin('users', 'users.id', '=', 'links.created_by')
            ->leftjoin('domains', 'domains.id', '=', 'links.domain_id')
            ->select([
                'links.id',
                'links.slug',
                'domains.url',
                'links.thumbnail_image',
                'links.title',
                'links.status',
                'links.created_by',
                'links.created_at',
                'users.first_name as user_name',
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
        return DB::transaction(function () use ($input) {
            $input['slug'] = Str::slug($input['title']);
            $input['created_by'] = auth()->user()->id;

            $input = $this->uploadImage($input);

            if ($link = Link::create($input)) {

                event(new LinkCreated($link));

                return $link;
            }

            throw new GeneralException(__('exceptions.backend.links.create_error'));
        });
    }

    /**
     * @param \App\Models\Link $link
     * @param array $input
     */
    public function update(Link $link, array $input)
    {

        $input['slug'] = Str::slug($input['title']);
        $input['updated_by'] = auth()->user()->id;

        // Uploading Image
        if (array_key_exists('thumbnail_image', $input)) {
            $this->deleteOldFile($link);
            $input = $this->uploadImage($input);
        }

        return DB::transaction(function () use ($link, $input) {
            if ($link->update($input)) {

                event(new LinkUpdated($link));

                return $link->fresh();
            }

            throw new GeneralException(__('exceptions.backend.links.update_error'));
        });
    }

    /**
     * @param \App\Models\Link $link
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Link $link)
    {
        DB::transaction(function () use ($link) {
            if ($link->delete()) {

                event(new LinkDeleted($link));

                return true;
            }

            throw new GeneralException(__('exceptions.backend.links.delete_error'));
        });
    }

    /**
     * Upload Image.
     *
     * @param array $input
     *
     * @return array $input
     */
    public function uploadImage($input)
    {
        if (isset($input['thumbnail_image']) && ! empty($input['thumbnail_image'])) {
            $avatar = $input['thumbnail_image'];
            $fileName = time().$avatar->getClientOriginalName();

            $this->storage->put($this->upload_path.$fileName, file_get_contents($avatar->getRealPath()));

            $input = array_merge($input, ['thumbnail_image' => $fileName]);
        }

        return $input;
    }

    /**
     * Destroy Old Image.
     *
     * @param int $id
     */
    public function deleteOldFile($model)
    {
        $fileName = $model->thumbnail_image;

        return $this->storage->delete($this->upload_path.$fileName);
    }
}
