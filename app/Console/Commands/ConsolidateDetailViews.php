<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsolidateDetailViews extends Command
{
    protected $signature = 'views:consolidatedetail';
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
            $this->info('Delete records in the views_counts_by_day table for today...');
            // Delete records in the views_counts_by_day table for today
            DB::table('views_counts_by_day')
                ->whereDate('date', '=', Carbon::today())
                ->delete();

            $endTime = Carbon::now();
            $this->info('Time taken for section 1: ' . $startTime->floatDiffInSeconds($endTime) . ' seconds');
            $startTime = $endTime;

            $this->info('Check if a record for the current month already exists in the views_counts_by_month table');
            // Check if a record for the current month already exists in the views_counts_by_month table
            $existingRecord = DB::table('views_counts_by_month')
                ->whereYear('year', '=', Carbon::now()->year)
                ->whereMonth('month', '=', Carbon::now()->month)
                ->exists();

            // If the record exists, update it; otherwise, insert a new one
            if ($existingRecord) {
                DB::table('views_counts_by_month')
                    ->whereYear('year', '=', Carbon::now()->year)
                    ->whereMonth('month', '=', Carbon::now()->month)
                    ->update([
                        'viewed' => DB::raw('(SELECT SUM(views.viewed) FROM views WHERE YEAR(views.date) = YEAR(CURRENT_DATE()) AND MONTH(views.date) = MONTH(CURRENT_DATE()))')
                    ]);
            } else {
                DB::table('views_counts_by_month')->insert([
                    'link_id' => DB::raw('(SELECT links.id FROM views JOIN links ON views.slug = links.slug GROUP BY links.id LIMIT 1)'),
                    'year' => Carbon::now()->year,
                    'month' => Carbon::now()->month,
                    'viewed' => DB::raw('(SELECT SUM(views.viewed) FROM views WHERE YEAR(views.date) = YEAR(CURRENT_DATE()) AND MONTH(views.date) = MONTH(CURRENT_DATE()))')
                ]);
            }

            $endTime = Carbon::now();
            $this->info('Time taken for section 2: ' . $startTime->floatDiffInSeconds($endTime) . ' seconds');
            $startTime = $endTime;

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
                ->groupBy('links.id', 'date', 'hour');
            });

            $endTime = Carbon::now();
            $this->info('Time taken for section 3: ' . $startTime->floatDiffInSeconds($endTime) . ' seconds');
            $startTime = $endTime;

            $this->info('Select view counts grouped by slug for today');
            // Select view counts grouped by slug for today
            $viewCountsBySlug = DB::table('views')
                ->select('slug', DB::raw('SUM(viewed) AS total_viewed'))
                ->whereDate('date', '=', Carbon::today())
                ->groupBy('slug');

            $this->info("Update the 'viewed' field in the 'links' table based on total views for each slug");
            // Update the 'viewed' field in the 'links' table based on total views for each slug
            DB::table('links')
                ->joinSub($viewCountsBySlug, 'v', function ($join) {
                    $join->on('links.slug', '=', 'v.slug');
                })
                ->update(['viewed' => DB::raw('v.total_viewed')]);
                
            $endTime = Carbon::now();
            $this->info('Time taken for section 4: ' . $startTime->floatDiffInSeconds($endTime) . ' seconds');
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
