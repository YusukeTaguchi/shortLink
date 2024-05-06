<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Link;
use App\Models\View as Views;
use App\Models\ViewsCountsByDay;
use App\Models\ViewsCountsByHour;
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
        $year = date('Y');

        $linkStatsQuery = Link::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', '=', $year)
        ->groupBy(DB::raw('MONTH(created_at)'));

        // Thêm điều kiện cho người dùng không phải là quản trị viên
        if (!auth()->user()->isAdmin()) {
            $linkStatsQuery->where('created_by', auth()->user()->id);
        }

        // Lấy dữ liệu từ truy vấn
        $linkStats = $linkStatsQuery->get();

        // Tạo một mảng để lưu trữ số liệu thống kê cho từng tháng
        $monthlyStats = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyStats[$month] = [
                'link_stats' => 0,
                'view_stats' => 0,
            ];
        }

        foreach ($linkStats as $stats) {
            $monthlyStats[$stats->month]['link_stats'] += $stats->count;
        }

        $viewStats = DB::table('views_counts_by_month')
            ->select('month', DB::raw('SUM(views_counts_by_month.viewed) as count'))
            ->where('year', '=', $year) 
            ->groupBy('month');

        if (!auth()->user()->isAdmin()) {
            $viewStats->join('links', 'views_counts_by_month.link_id', '=', 'links.id')
                ->where('links.created_by', auth()->user()->id);
        }

        $viewStats = $viewStats->get();

        foreach ($viewStats as $stats) {
            $monthlyStats[$stats->month]['view_stats'] = $stats->count;
        }

        $currentDate = Carbon::now()->toDateString();

        $linksToday = DB::table('views_counts_by_day')
            ->whereDate('date', $currentDate);

        if (!auth()->user()->isAdmin()) {
            $linksToday->join('links', 'views_counts_by_day.link_id', '=', 'links.id')
                ->where('links.created_by', auth()->user()->id);
        }

        $linksToday = $linksToday->count();

        $viewsToday = DB::table('views_counts_by_day')
            ->join('links', 'views_counts_by_day.link_id', '=', 'links.id') 
            ->whereDate('date', $currentDate);

        if (!auth()->user()->isAdmin()) {
            $viewsToday->where('links.created_by', auth()->user()->id);
        }

        $viewsToday = $viewsToday->sum('views_counts_by_day.viewed');

        $currentMonth = Carbon::now()->month;
        $linksThisMonth = DB::table('views_counts_by_month')
            ->where('year', '=', $year) 
            ->where('month', '=', $currentMonth);

        if (!auth()->user()->isAdmin()) {
            $linksThisMonth->join('links', 'views_counts_by_month.link_id', '=', 'links.id')
                ->where('links.created_by', auth()->user()->id);
        }

        $linksThisMonth = $linksThisMonth->count();
        $viewsThisMonth = DB::table('views_counts_by_month')
            ->join('links', 'views_counts_by_month.link_id', '=', 'links.id') 
            ->where('year', '=', $year) 
            ->where('month', '=', $currentMonth);

        if (!auth()->user()->isAdmin()) {
            $viewsThisMonth->where('links.created_by', auth()->user()->id);
        }

        $viewsThisMonth = $viewsThisMonth->sum('views_counts_by_month.viewed');

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
        
        $viewStatsQuery = ViewsCountsByHour::select(
            'hour',
            DB::raw('SUM(views_counts_by_hour.viewed) as count')
        )
        ->join('links', 'links.id', '=', 'views_counts_by_hour.link_id')
        ->whereDate('views_counts_by_hour.date', '=', now()->toDateString())
        ->groupBy('hour')
        ->orderBy('hour');
        
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
        $viewStatsQuery = ViewsCountsByDay::select(
            DB::raw('DAY(views_counts_by_day.date) as day'),
            DB::raw('SUM(views_counts_by_day.viewed) as count')
        )
        ->whereYear('date', '=', now()->year)
        ->whereMonth('date', '=', now()->month)
        ->groupBy(DB::raw('DAY(views_counts_by_day.date)'));
        
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
