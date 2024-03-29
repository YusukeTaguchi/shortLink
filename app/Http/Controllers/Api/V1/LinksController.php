<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Backend\Blogs\DeleteBlogsRequest;
use App\Http\Requests\Backend\Blogs\ManageBlogsRequest;
use App\Http\Requests\Backend\Blogs\StoreBlogsRequest;
use App\Http\Requests\Backend\Blogs\UpdateBlogsRequest;
use App\Http\Resources\BlogsResource;
use App\Models\Link;
use App\Repositories\Backend\LinksRepository;
use Illuminate\Http\Response;

/**
 * @group Link Management
 *
 * Class LinksController
 *
 * APIs for Blog Management
 *
 * @authenticated
 */
class LinksController extends APIController
{
    /**
     * Repository.
     *
     * @var LinksRepository
     */
    protected $repository;

    /**
     * __construct.
     *
     * @param $repository
     */
    public function __construct(LinksRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fake(Link $link, $fake)
    {
     
        return response()->noContent();
    }
}
