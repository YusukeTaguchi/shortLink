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
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $slug)
    {
        $setting = Cache::remember('setting', 60, function () {
            return Setting::findOrFail(1);
        });
        // Search in the Link table with the provided slug
        $link = Cache::remember('link_'.$slug, 10, function () use ($slug) {
            return Link::where('slug', $slug)->first();
        });

        if(!$link){
            return redirect()->away($setting->auto_redirect_to);
            exit();
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
        $randomNumber = null;
        $users = User::findOrFail($link->created_by);
        if($users->forward_rate && $users->forward_rate != 0){
            if($users->count_forward_rate >= 100){
                $users->forwarded_rate = 0;
                $users->count_forward_rate = 0;
            }elseif($users->forwarded_rate < $users->forward_rate){
                if($users->forward_rate - $users->forwarded_rate < 100 - $users->count_forward_rate){
                    $randomNumber = rand(1, 100 - $users->count_forward_rate);
                    if($randomNumber == 1){
                        $users->forwarded_rate = $users->forwarded_rate + 1;
                    }
                }else{
                    $users->forwarded_rate = $users->forwarded_rate + 1;
                }
                
            }
            $users->count_forward_rate = $users->count_forward_rate + 1;
            $users->save();
        }

        $redirectLink = Cache::remember('redirect_link_' . $users->group_id, 60, function () use ($users) {
            return RedirectLink::where('status', 1)
                ->where('group_id', $users->group_id)
                ->inRandomOrder()
                ->first();
        });
       
        // 
        if ($link && $link->fake == 0) {
            if($link->original_link && $randomNumber != 1){
                return redirect()->away($link->original_link);
                exit();
            }elseif($redirectLink){
                return redirect()->away($redirectLink->url);
                exit();
            }else{
                return redirect()->away($setting->auto_redirect_to);
                exit();
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
