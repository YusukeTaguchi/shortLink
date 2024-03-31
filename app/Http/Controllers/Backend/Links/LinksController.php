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
        $domains = Domain::getSelectData();
        return new ViewResponse('backend.links.create', ['status' => $link->statuses, 'domains' => $domains]);
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
        return new EditResponse($link, $domains, $link->statuses);
    }

    // Function to send request to Facebook Debug Tool and handle response
    private function debugUrl($url)
    {
        // URL of Facebook's Debug Tool
        $debuggerUrl = 'https://developers.facebook.com/tools/debug/';

        // Create an instance of the HTTP client
        $httpClient = new Client();

        // Send a POST request to Facebook's Debug Tool
        $response = $httpClient->get($debuggerUrl, [
            'query' => [
                'id' => $url,  // URL to debug
                'scrape' => 'true'  // Force scraping of the URL content
            ]
        ]);
        // Check the response from Facebook's Debug Tool
        if ($response->getStatusCode() == 200) {
            // If the response is successful, handle the debug result
            return $response->getBody()->getContents();
        } else {
            // If the response is not successful, handle the error
            return false;
        }
    }

    // Function to synchronize link and debug URL
    private function syncAndDebug(Link $link)
    {
        // Build the URL of the webpage based on the link's slug
        $url = $link->url . '/' . $link->slug;

        // Send request to Facebook Debug Tool and handle response
        $debugResult = $this->debugUrl($url);

        return $debugResult;
    }

    // Function to sync link and debug URL
    public function sync($id, ManageLinksRequest $request)
    {
        $link = Link::with('domain')->findOrFail($id);

        // Sync the link
        $debugResult = $this->syncAndDebug($link);

        if ($debugResult !== false) {
            return new RedirectResponse(route('admin.links.index'), ['flash_success' => __('alerts.backend.links.sync')]);
        } else {
            return new RedirectResponse(route('admin.links.index'), ['flash_error' => __('alerts.backend.links.sync')]);
        }
    }

    // Function to update fake status and debug URL
    public function fake($id, $fake, ManageLinksRequest $request)
    {
        Link::where('id', $id)->update(['fake' => $fake]);
        $link = Link::with('domain')->findOrFail($id);

        // Sync the link
        $debugResult = $this->syncAndDebug($link);

        if ($debugResult !== false) {
            return new RedirectResponse(route('admin.links.index'), ['flash_success' => __('alerts.backend.links.sync')]);
        } else {
            return new RedirectResponse(route('admin.links.index'), ['flash_error' => __('alerts.backend.links.sync')]);
        }
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
