<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsolidateDetailDayViews extends Command
{
    protected $signature = 'views:consolidatedetailday';
    protected $description = 'Consolidate views for each day into a single record and delete old records';

    public function handle()
    {
        // Begin transaction
        DB::beginTransaction();
        $this->info('Start...');
        $startTime = Carbon::now();
        try {
            $this->info('Clear all records in the views_counts_by_hour table...');
            // Clear all records in the views_counts_by_hour table
            DB::table('views_counts_by_hour')->truncate();

            $this->info('Insert data into the views_counts_by_hour table');
            // Insert data into the views_counts_by_hour table
            DB::table('views_counts_by_hour')->insertUsing(['link_id', 'viewed', 'date', 'hour'], function($query) {
                $query->select([
                    'links.id AS link_id',
                    DB::raw('SUM(views.viewed) AS viewed'),
                    DB::raw('DATE(views.date) AS date'),
                    DB::raw('HOUR(views.date) AS hour')
                ])->from('views')
                ->join('links', 'views.slug', '=', 'links.slug')
                ->whereDate('views.date', '=', Carbon::today())
                ->groupBy('links.id', 'hour');
            });
            
            $startTime = $endTime;
            $this->info("commit...");
            // Commit transaction
            DB::commit();

            // Log success
            $this->info('Detail views consolidated successfully.');

        } catch (\Exception $e) {
            // Rollback transaction and log error
            DB::rollback();
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
