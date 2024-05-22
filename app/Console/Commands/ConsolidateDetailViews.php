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
           
            $this->info('Delete records in the views_counts_by_day table for today...');
            // Delete records in the views_counts_by_day table for today
            DB::table('views_counts_by_day')
                ->whereDate('date', '=', Carbon::today())
                ->delete();
                
            DB::statement("
                INSERT INTO views_counts_by_day (link_id, viewed, date)
                SELECT 
                    l.id AS link_id,
                    SUM(v.viewed) AS viewed,
                    DATE(v.date) AS date
                FROM views v
                JOIN links l ON v.slug = l.slug
                WHERE YEAR(v.date) = YEAR(CURRENT_DATE()) AND MONTH(v.date) = MONTH(CURRENT_DATE()) AND DATE(v.date) = DATE(CURRENT_DATE())
                GROUP BY link_id, DATE(v.date);
            ");

            $endTime = Carbon::now();
            $this->info('Time taken for section 1: ' . $startTime->floatDiffInSeconds($endTime) . ' seconds');
            $startTime = $endTime;

            $this->info('Check if a record for the current month already exists in the views_counts_by_month table');

            DB::table('views_counts_by_month')
                ->where('year', '=', Carbon::now()->year)
                ->where('month', '=', Carbon::now()->month)
                ->delete();
            
            $endTime = Carbon::now();
            $this->info('Time taken for section 2: ' . $startTime->floatDiffInSeconds($endTime) . ' seconds');
            $startTime = $endTime;

            // If the record exists, update it; otherwise, insert a new one
            DB::statement("
                INSERT INTO views_counts_by_month (link_id, `year`, `month`, viewed)
                SELECT 
                    l.id AS link_id,
                    YEAR(v.date) AS `year`,
                    MONTH(v.date) AS `month`,
                    SUM(v.viewed) AS viewed
                FROM views v
                JOIN links l ON v.slug = l.slug
                WHERE YEAR(v.date) = YEAR(CURDATE()) AND MONTH(v.date) = MONTH(CURRENT_DATE())
                GROUP BY link_id, `year`, `month`
            ");
            

            $endTime = Carbon::now();
            $this->info('Time taken for section 3: ' . $startTime->floatDiffInSeconds($endTime) . ' seconds');
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
