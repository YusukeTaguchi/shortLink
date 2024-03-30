<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\RedirectLink;
use App\Models\Setting;
use App\Models\View;
use App\Models\Auth\User;
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
        $setting = Setting::findOrFail(1);
        // Search in the Link table with the provided slug
        $link = Link::where('slug', $slug)->first();

        if(!$link){
            return redirect()->away($setting->auto_redirect_to);
        }

        $isFromFacebook = $request->has('fbclid');

        if($isFromFacebook){
            // Count
            View::create([
                'slug' => $slug,
                'viewed' => 1,
                'date' => now()->format('Y-m-d H:i:s')
            ]);
        }
        // If no link is found, retrieve information from the Setting table with id=1
    
        $users = User::findOrFail($link->created_by);
        $redirectLink = RedirectLink::where('status', 1)->where('group_id', $users->group_id)->inRandomOrder()->first();
        // 
        if ($link && $link->fake == 0) {
            if($link->original_link){
                return redirect()->away($link->original_link);
            }elseif($redirectLink){
                
                return redirect()->away($redirectLink->url);
            }else{
                return redirect()->away($setting->auto_redirect_to);
            }
           
        }

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
        $containsAppUrl = strpos(config('app.url'), $domain) !== false;
        if(!$containsAppUrl){
            // If no link is found, retrieve information from the Setting table with id=1
            $setting = Setting::findOrFail(1);
            return redirect()->away($setting->auto_redirect_to);
        }
        // If a link is found, return the view with the link data
        return view('frontend.auth.login');
    }
}
