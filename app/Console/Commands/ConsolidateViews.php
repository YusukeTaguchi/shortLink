<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsolidateViews extends Command
{
    protected $signature = 'views:consolidate';
    protected $description = 'Consolidate views for each day into a single record and delete old records';

    public function handle()
    {
        $currentDate = Carbon::today();
        $firstDayOfMonth = $currentDate->copy()->subMonth()->startOfMonth();
        $firstDayOfMonthPre = $currentDate->copy()->subMonths(1)->startOfMonth();
        $lastDayOfMonth = $currentDate->copy()->endOfMonth();
       

        DB::beginTransaction();

        try {
            for ($date = $firstDayOfMonthPre; $date <= $lastDayOfMonth; $date->addDay()) {
                if (!$date->isSameDay($currentDate)) {
                    print_r("Date: '$date' ". "\n");
                    // Lấy tất cả views cho ngày hiện tại
                    $views = DB::table('views')
                        ->whereDate('date', $date)
                        ->where(function ($query) {
                            $query->where('is_consolidated', '!=', 1)
                                  ->orWhereNull('is_consolidated');
                        })
                        ->get();

                    // Tạo hoặc cập nhật một record cho ngày hiện tại
                    $consolidatedViews = [];
                    foreach ($views as $view) {
                        $slug = $view->slug;
                        $viewed = $view->viewed;

                        if (!isset($consolidatedViews[$slug])) {
                            $consolidatedViews[$slug] = $viewed;
                        } else {
                            $consolidatedViews[$slug] += $viewed;
                        }
                    }

                    foreach ($consolidatedViews as $slug => $viewed) {
                        DB::table('views')->updateOrInsert(
                            ['slug' => $slug, 'date' => $date],
                            ['viewed' => DB::raw("viewed + $viewed"), 'is_consolidated' => 1]
                        );
                    }

                    // Xóa các records views cũ
                    DB::table('views')
                        ->whereDate('date', $date)
                        ->whereNull('is_consolidated') 
                        ->delete();
                }
            }

            DB::commit();
            $this->info('Views consolidated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
