<?php

namespace App\Http\Controllers\Backend\Links;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Links\ManageLinksRequest;
use App\Http\Requests\Backend\Links\StoreLinksRequest;
use App\Http\Requests\Backend\Links\UpdateLinksRequest;
use App\Http\Responses\Backend\Link\EditResponse;
use App\Http\Responses\RedirectResponse;
use App\Http\Responses\ViewResponse;
use App\Models\Link;
use App\Models\Domain;
use App\Repositories\Backend\LinksRepository;
use Illuminate\Support\Facades\View;
use GuzzleHttp\Client;

class LinksController extends Controller
{
    /**
     * @var \App\Repositories\Backend\LinksRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\Backend\LinksRepository $link
     */
    public function __construct(LinksRepository $repository)
    {
        $this->repository = $repository;
        View::share('js', ['links']);
    }

    /**
     * @param \App\Http\Requests\Backend\Links\ManageLinksRequest $request
     *
     * @return ViewResponse
     */
    public function index(ManageLinksRequest $request)
    {
        return new ViewResponse('backend.links.index');
    }

    /**
     * @param \App\Http\Requests\Backend\Links\ManageLinksRequest $request
     *
     * @return ViewResponse
     */
    public function create(ManageLinksRequest $request, Link $link)
    {

        return new ViewResponse('backend.links.create', ['status' => $link->statuses]);
    }

    /**
     * @param \App\Http\Requests\Backend\Links\StoreLinksRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function store(StoreLinksRequest $request)
    {
        $this->repository->create($request->except(['_token', '_method']));

        return new RedirectResponse(route('admin.links.index'), ['flash_success' => __('alerts.backend.links.created')]);
    }

    /**
     * @param \App\Models\Link $link
     * @param \App\Http\Requests\Backend\Links\ManageLinksRequest $request
     *
     * @return \App\Http\Responses\Backend\Link\EditResponse
     */
    public function edit(Link $link, ManageLinksRequest $request)
    {
        $domains = Domain::getSelectData();
        // dd($domains);
        return new EditResponse($link, $domains, $link->statuses);
    }

     /**
     * @param \App\Models\Link $link
     * @param \App\Http\Requests\Backend\Links\ManageLinksRequest $request
     *
     * @return \App\Http\Responses\Backend\Link\EditResponse
     */
    public function sync($id, ManageLinksRequest $request)
    {
        $link = Link::find($id);

        // Build the URL of the webpage based on the link's slug
        $url = config('app.url') . '/' . $link->slug;

        // URL of Facebook's Debug Tool
        $debuggerUrl = 'https://developers.facebook.com/tools/debug/';

        // Create an instance of the HTTP client
        $httpClient = new Client();

        // Send a POST request to Facebook's Debug Tool
        $response = $httpClient->post($debuggerUrl, [
            'id' => $url,  // URL to debug
            'scrape' => 'true'  // Force scraping of the URL content
        ]);

        // Check the response from Facebook's Debug Tool
        if ($response->getStatusCode() == 200) {
            // If the response is successful, handle the debug result
            $debugResult = $response->getBody()->getContents();
        } else {
            // If the response is not successful, handle the error
            return new RedirectResponse(route('admin.links.index'), ['flash_success' => __('alerts.backend.links.created')]);
        }
        return new RedirectResponse(route('admin.links.index'), ['flash_success' => __('alerts.backend.links.created')]);
    }

    /**
     * @param \App\Models\Link $link
     * @param \App\Http\Requests\Backend\Links\UpdateLinksRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function update(Link $link, UpdateLinksRequest $request)
    {
        $this->repository->update($link, $request->except(['_token', '_method']));

        return new RedirectResponse(route('admin.links.index'), ['flash_success' => __('alerts.backend.links.updated')]);
    }

    /**
     * @param \App\Models\Link $link
     * @param \App\Http\Requests\Backend\Links\ManageLinksRequest $request
     *
     * @return \App\Http\Responses\RedirectResponse
     */
    public function destroy(Link $link, ManageLinksRequest $request)
    {
        $this->repository->delete($link);

        return new RedirectResponse(route('admin.links.index'), ['flash_success' => __('alerts.backend.links.deleted')]);
    }
}
