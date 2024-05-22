<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsolidateDetailLinkViews extends Command
{
    protected $signature = 'views:consolidatedetaillink';
    protected $description = 'Consolidate views for each day into a single record and delete old records';

    public function handle()
    {
        // Begin transaction
        DB::beginTransaction();
        $this->info('Start...');
        $startTime = Carbon::now();
        try {

            $this->info('Select view counts grouped by slug for today');
            // Select view counts grouped by slug for today
            $viewCountsBySlug = DB::table('views')
                ->select('slug', DB::raw('SUM(viewed) AS tl_viewed'))
                ->whereDate('date', '=', Carbon::today())
                ->groupBy('slug');

            $this->info("Update the 'viewed' field in the 'links' table based on total views for each slug");
            // Update the 'viewed' field in the 'links' table based on total views for each slug
            
            DB::table('links')
                ->leftJoinSub($viewCountsBySlug, 'v', function ($join) {
                    $join->on('links.slug', '=', 'v.slug');
                })
                ->whereNull('v.slug')
                ->update(['viewed' => 0]);

            DB::table('links')
                ->joinSub($viewCountsBySlug, 'v', function ($join) {
                    $join->on('links.slug', '=', 'v.slug');
                })
                ->update(['viewed' => DB::raw('v.tl_viewed')]);
            

            $viewAllCountsBySlug = DB::table('views')
                ->select('slug', DB::raw('SUM(viewed) AS tl_viewed'))
                ->groupBy('slug');
                
            DB::table('links')
                ->joinSub($viewAllCountsBySlug, 'v', function ($join) {
                    $join->on('links.slug', '=', 'v.slug');
                })
                ->update(['total_viewed' => DB::raw('v.tl_viewed')]);
                
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
