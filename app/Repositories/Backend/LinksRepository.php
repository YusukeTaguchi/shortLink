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

    public function getForDataTable()
    {
        $query = $this->query()
            ->leftJoin('users', 'users.id', '=', 'links.created_by')
            ->leftJoin('domains', 'domains.id', '=', 'links.domain_id')
            ->select([
                'links.id',
                'links.fake',
                'links.slug',
                'domains.url',
                'links.thumbnail_image',
                'links.title',
                'links.status',
                'links.created_by',
                'links.created_at',
                'users.first_name as user_name',
                'total_viewed'
            ])
            ->whereNull('links.deleted_at')
            ->groupBy('links.id', 'links.slug', 'domains.url', 'links.thumbnail_image', 'links.title', 'links.status', 'links.created_by', 'links.created_at', 'users.first_name')
            ->orderBy('links.id', 'desc');

        if (!auth()->user()->isAdmin()) {
            $query->where('links.created_by', auth()->user()->id);
        } 

        return $query;
    }


    public function getTopForDataTable()
    {
        $toDay = now()->toDateString();

        $query = $this->query()
            ->leftJoin('users', 'users.id', '=', 'links.created_by')
            ->leftJoin('domains', 'domains.id', '=', 'links.domain_id')
            ->select([
                'links.id',
                'links.fake',
                'links.slug',
                'domains.url',
                'links.thumbnail_image',
                'links.title',
                'links.status',
                'links.created_by',
                'links.created_at',
                'users.first_name as user_name',
                DB::raw('viewed as total_viewed')
            ])
            ->whereNull('links.deleted_at')
            ->groupBy('links.id', 'links.slug', 'domains.url', 'links.thumbnail_image', 'links.title', 'links.status', 'links.created_by', 'links.created_at', 'users.first_name')
            ->orderBy('total_viewed', 'desc');
    
        if (!auth()->user()->isAdmin()) {
          
            $query->where('links.created_by', auth()->user()->id);
        } 

        $startOfDay = now()->startOfDay();
        $endOfDay = now()->endOfDay();
    
        return $query->setBindings([auth()->user()->id]);
    }


    public function getMonthlyForDataTable()
    {
        $year = now()->year;
        $month = now()->month;

        $query = $this->query()
            ->leftJoin('users', 'users.id', '=', 'links.created_by')
            ->leftJoin('domains', 'domains.id', '=', 'links.domain_id')
             ->leftJoin('views_counts_by_month', function ($join) use($month, $year) {
                $join->on('views_counts_by_month.link_id', '=', 'links.id')
                    ->whereDate('views_counts_by_month.month', '=', $month)
                    ->whereDate('views_counts_by_month.year', '=', $year); 
            })
            ->select([
                'links.id',
                'links.fake',
                'links.slug',
                'domains.url',
                'links.thumbnail_image',
                'links.title',
                'links.status',
                'links.created_by',
                'links.created_at',
                'users.first_name as user_name',
                'views_counts_by_month.viewed as total_viewed'
            ])
            ->whereNull('links.deleted_at')
            ->groupBy('links.id', 'links.slug', 'domains.url', 'links.thumbnail_image', 'links.title', 'links.status', 'links.created_by', 'links.created_at', 'users.first_name')
            ->orderBy('total_viewed', 'desc');

        if (!auth()->user()->isAdmin()) {
            $query->where('links.created_by', auth()->user()->id);
        } 

        return $query->setBindings([ auth()->user()->id]);
    }

    
    public function generateUniqueCode() {
        return time();
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
            $input['fake'] = $input['fake'] ?? 0;
            $input['slug'] = Str::slug($input['title']);
            $input['created_by'] = auth()->user()->id;

            // Upload image if needed
            $input = $this->uploadImage($input);

            // Check if the slug already exists in the database
            $originalSlug = $input['slug'];
            $codeString = $this->generateUniqueCode(); // You need to define this function to generate a unique code string
            $counter = 1;

            while (Link::where('slug', $input['slug'])->exists()) {
                $input['slug'] = $originalSlug . '-' . $codeString . '-' . $counter;
                $counter++;
            }

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

        $input['slug'] = Str::slug($input['slug']);
        $input['fake'] = $input['fake'] ?? 0;
        $input['updated_by'] = auth()->user()->id;

        // Uploading Image
        if (array_key_exists('thumbnail_image', $input)) {
            $this->deleteOldFile($link);
            $input = $this->uploadImage($input);
        }

        return DB::transaction(function () use ($link, $input) {

            $originalSlug = $input['slug'];
            $codeString = $this->generateUniqueCode(); // You need to define this function to generate a unique code string
            $counter = 1;

            while (Link::where('slug', $input['slug'])->where('id', '!=', $link->id)->exists()) {
                $input['slug'] = $originalSlug . '-' . $codeString . '-' . $counter;
                $counter++;
            }

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
