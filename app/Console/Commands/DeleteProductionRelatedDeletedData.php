<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteProductionRelatedDeletedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:production-related-deleted-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Production Related Deleted Data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $this->info('Execution Started');
            $date = Carbon::now()->subDays(90)->toDateString();
            $this->info('Deleted data before '.$date);

            DB::statement("DELETE FROM `cutting_targets` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `sewing_line_targets` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `bundle_card_generation_details` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `bundle_cards` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `cutting_inventories` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `cutting_inventory_challans` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `print_inventories` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `print_inventory_challans` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `sewingoutputs` WHERE `deleted_at` IS NOT NULL AND DATE_FORMAT(`deleted_at`, '%Y-%m-%d') <= '$date';");
            DB::statement("DELETE FROM `bundle_card_generation_caches` WHERE DATE_FORMAT(`created_at`, '%Y-%m-%d') <= '$date';");
            
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }
}
