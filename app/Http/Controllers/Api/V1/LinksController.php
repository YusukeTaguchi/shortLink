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
use GuzzleHttp\Client;

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

    public function fake($id, $fake)
    {
        Link::where('id', $id)->update(['fake' => $fake]);
        $link = Link::with('domain')->findOrFail($id);

        // Sync the link
        $debugResult = $this->syncAndDebug($link);

        if ($debugResult !== false) {
            return response()->json([
                "status_code" => 200
            ]);
        } else {
            return response()->json([
                "status_code" => 200
            ]);
        }

    }

    // Function to send request to Facebook Debug Tool and handle response
    private function debugUrl($url)
    {
        // $url = 'http://hdzonline.pro/full-chang-trai-lui-ve-o-an-giup-gia-dinh-vo-nao-ngo-bi-dam-sau-lung';
        // URL of Facebook's Debug Tool
        $debuggerUrl = 'https://developers.facebook.com/tools/debug/';

        // Create an instance of the HTTP client
        $httpClient = new Client();

        // Send a POST request to Facebook's Debug Tool
        $response = $client->get($debuggerUrl, [
            'query' => [
                'q' => $url,  // URL to debug
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
}
