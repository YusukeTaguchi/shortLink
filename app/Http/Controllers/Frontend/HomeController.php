<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\RedirectLink;
use App\Models\Setting;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $slug)
    {
        // Search in the Link table with the provided slug
        $link = Link::where('slug', $slug)->first();

        // Count
        View::create([
            'slug' => $slug,
            'viewed' => 1,
            'date' => now()->format('Y-m-d H:i:s')
        ]);

        $redirectLink = RedirectLink::where('status', 1)->inRandomOrder()->first();
        // 
        if ($link && $link->fake == 0) {
            if($link->original_link){
                return redirect()->away($link->original_link);
            }else{
                return redirect()->away($redirectLink->url);
            }
           
        }

        // If no link is found, retrieve information from the Setting table with id=1
        $setting = Setting::findOrFail(1);

        // If a link is found, return the view with the link data
        return view('frontend.index', ['link' => $link, 'setting' => $setting, 'redirectLink' => $redirectLink]);
    }


     /**
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function home(Request $request)
    {
        $domain = $request->getHost();
        $containsAppUrl = Str::contains($domain, config('app.url'));
        if(!$containsAppUrl){
            // If no link is found, retrieve information from the Setting table with id=1
            $setting = Setting::findOrFail(1);
            return redirect()->away($setting->auto_redirect_to);
        }
        // If a link is found, return the view with the link data
        return view('frontend.auth.login');
    }
}
