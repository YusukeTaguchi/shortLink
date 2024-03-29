<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Link;
use App\Models\View as Views;
use Carbon\Carbon;
use DB;
/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
   
    public function __construct()
    {
       
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $monthlyStats = [];
        $year = date('Y');

        // Vòng lặp qua từng tháng trong năm (từ tháng 1 đến tháng 12)
        for ($month = 1; $month <= 12; $month++) {
            // Truy vấn số liệu thống kê cho link trong tháng này
            $linkStatsQuery = Link::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', '=', $year) // $year là năm muốn thống kê
                ->whereMonth('created_at', '=', $month)
                ->groupBy(DB::raw('MONTH(created_at)'));

            // Truy vấn số liệu thống kê cho lượt xem trong tháng này
            $viewStatsQuery = Views::select(
                    DB::raw('MONTH(views.date) as month'),
                    DB::raw('SUM(views.viewed) as count')
                )
                ->leftJoin('links', 'links.slug', '=', 'views.slug')
                ->whereYear('views.date', '=', $year) // $year là năm muốn thống kê
                ->whereMonth('views.date', '=', $month)
                ->groupBy(DB::raw('MONTH(views.date)'));

            // Thêm điều kiện cho người dùng không phải là quản trị viên
            if (!auth()->user()->isAdmin()) {
                $linkStatsQuery->where('links.created_by', auth()->user()->id);
                $viewStatsQuery->where('links.created_by', auth()->user()->id);
            }

            // Lấy dữ liệu từ các truy vấn
            $linkStats = $linkStatsQuery->count();
            $viewStats = $viewStatsQuery->sum('views.viewed');

            // Lưu trữ dữ liệu vào mảng $monthlyStats
            $monthlyStats[$month] = [
                'link_stats' => $linkStats,
                'view_stats' => $viewStats,
            ];
            
        }


        // Lấy ngày hiện tại
        $currentDate = Carbon::now()->toDateString();

        // Truy vấn tổng số lượng link của ngày hiện tại
        $linksToday = DB::table('links')
            ->whereDate('created_at', $currentDate);
        
        if (!auth()->user()->isAdmin()) {
            $linksToday =$linksToday->where('links.created_by', auth()->user()->id);
        }
        $linksToday = $linksToday->count();

        // Truy vấn tổng số lượng view của ngày hiện tại
        $viewsToday = DB::table('views')
            ->join('links', 'views.slug', '=', 'links.slug') // Join với bảng links
            ->whereDate('views.date', $currentDate);
        if (!auth()->user()->isAdmin()) {
            $viewsToday =$viewsToday->where('links.created_by', auth()->user()->id);
        }
        $viewsToday = $viewsToday->sum('views.viewed');

        // Lấy tháng hiện tại
        $currentMonth = Carbon::now()->month;

        // Truy vấn tổng số lượng link của tháng hiện tại
        $linksThisMonth = DB::table('links')
            ->whereMonth('created_at', $currentMonth);
        if (!auth()->user()->isAdmin()) {
            $linksThisMonth =$linksThisMonth->where('links.created_by', auth()->user()->id);
        }
        $linksThisMonth = $linksThisMonth->count();


        // Truy vấn tổng số lượng view của tháng hiện tại
        $viewsThisMonth = DB::table('views')
            ->leftJoin('links', 'views.slug', '=', 'links.slug') // Join với bảng links
            ->whereMonth('views.date', $currentMonth)
            ->whereYear('views.date', '=', $year) ;

        if (!auth()->user()->isAdmin()) {
            $viewsThisMonth =$viewsThisMonth->where('links.created_by', auth()->user()->id);
        }
        $viewsThisMonth = $viewsThisMonth->sum('views.viewed');

        return view('backend.dashboard',  compact('monthlyStats', 'linksToday', 'viewsToday', 'linksThisMonth', 'viewsThisMonth'));
    }


     /**
     * @return \Illuminate\View\View
     */
    public function today()
    {
        View::share('js', ['today']);

        $linkStats = array_fill(0, 25, ['hour' => 0, 'count' => 0]);
        $viewStats = array_fill(0, 25, ['hour' => 0, 'count' => 0]);

        $linkStatsQuery = Link::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as count')
        )
        ->whereDate('created_at', '=', now()->toDateString())
        ->groupBy(DB::raw('HOUR(created_at)'))
        ->orderBy(DB::raw('HOUR(created_at)'));
       
        
        if (!auth()->user()->isAdmin()) {
            $linkStatsQuery->where('links.created_by', auth()->user()->id);
        } 
      
        $linkStatsQuery = $linkStatsQuery->get();
        
        
        $viewStatsQuery = Views::select(
            DB::raw('HOUR(views.date) as hour'),
            DB::raw('SUM(views.viewed) as count')
        )
        ->leftJoin('links', 'links.slug', '=', 'views.slug')
        ->whereDate('views.date', '=', now()->toDateString())
        ->groupBy(DB::raw('HOUR(views.date)'))
        ->orderBy(DB::raw('HOUR(views.date)'));
        
        if (!auth()->user()->isAdmin()) {
            $viewStatsQuery->where('links.created_by', auth()->user()->id);
        } 

       
        
        $viewStatsQuery = $viewStatsQuery->get();
        
        foreach ($linkStatsQuery as $linkStat) {
            $linkStats[$linkStat->hour]['hour'] = $linkStat->hour;
            $linkStats[$linkStat->hour]['count'] = $linkStat->count;
        }
        
        foreach ($viewStatsQuery as $viewStat) {
            $viewStats[$viewStat->hour]['hour'] = $viewStat->hour;
            $viewStats[$viewStat->hour]['count'] = $viewStat->count;
        }
        
        
        $stats = [
            'link_stats' => array_values($linkStats),
            'view_stats' => array_values($viewStats),
        ];


        return view('backend.dashboard.today',  compact('stats'));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function monthly()
    {
        View::share('js', ['monthly']);

        $daysInMonth = now()->daysInMonth;
        $linkStats = [];
        $viewStats = [];

        // Tạo mảng chứa số liệu cho mỗi ngày trong tháng
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $linkStats[$day] = ['day' => $day, 'count' => 0];
            $viewStats[$day] = ['day' => $day, 'count' => 0];
        }

        // Truy vấn và đếm số lượng liên kết được tạo cho mỗi ngày trong tháng
        $linkStatsQuery = Link::select(
            DB::raw('DAY(created_at) as day'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', '=', now()->year)
        ->whereMonth('created_at', '=', now()->month)
        ->groupBy(DB::raw('DAY(created_at)'));

        if (!auth()->user()->isAdmin()) {
            $linkStatsQuery->where('links.created_by', auth()->user()->id);
        } 


        $linkStatsQuery = $linkStatsQuery->get();

        foreach ($linkStatsQuery as $linkStat) {
            $linkStats[$linkStat->day]['count'] = $linkStat->count;
        }

        // Truy vấn và đếm số lượng lượt xem cho mỗi ngày trong tháng
        $viewStatsQuery = Views::select(
            DB::raw('DAY(views.date) as day'),
            DB::raw('SUM(views.viewed) as count')
        )
        ->leftJoin('links', 'links.slug', '=', 'views.slug')
        ->whereYear('views.date', '=', now()->year)
        ->whereMonth('views.date', '=', now()->month)
        ->groupBy(DB::raw('DAY(views.date)'));
        
        if (!auth()->user()->isAdmin()) {
            $viewStatsQuery->where('links.created_by', auth()->user()->id);
        } 
        $viewStatsQuery = $viewStatsQuery->get();

        foreach ($viewStatsQuery as $viewStat) {
            $viewStats[$viewStat->day]['count'] = $viewStat->count;
        }

        $stats = [
            'link_stats' => array_values($linkStats),
            'view_stats' => array_values($viewStats),
        ];



        return view('backend.dashboard.monthly',  compact('stats'));
    }

    /**
     * This function is used to get permissions details by role.
     *
     * @param \Illuminate\Http\Request\Request $request
     */
    public function getPermissionByRole(Request $request)
    {
        if ($request->ajax()) {
            $role_id = $request->get('role_id');
            $rsRolePermissions = Role::where('id', $role_id)->first();
            $rolePermissions = $rsRolePermissions->permissions->pluck('display_name', 'id')->all();
            $permissions = Permission::pluck('display_name', 'id')->all();
            ksort($rolePermissions);
            ksort($permissions);
            $results['permissions'] = $permissions;
            $results['rolePermissions'] = $rolePermissions;
            $results['allPermissions'] = $rsRolePermissions->all;
            echo json_encode($results);
            exit;
        }
    }
}
