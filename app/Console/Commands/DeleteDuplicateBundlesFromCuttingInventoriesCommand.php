<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteDuplicateBundlesFromCuttingInventoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:duplicate-bundles-from-cutting-inventories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Duplicate Bundles From CuttingInventory Model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info("Execution started successfully!");
            $date = now()->subDays(7)->startOfDay()->toDateTimeString();
            $duplicateData = DB::select("select * from cutting_inventories WHERE deleted_at IS NULL AND bundle_card_id IN (SELECT bundle_card_id FROM `cutting_inventories` WHERE deleted_at IS NULL AND created_at >= '$date' GROUP BY bundle_card_id HAVING COUNT(bundle_card_id) > 1) ORDER BY bundle_card_id ASC");
            $deletedIds = [];
            foreach (collect($duplicateData)->groupBy('bundle_card_id') as $dataBybundleCardId) {
                $dataBybundleCardId->each(function ($item ,$key) use(&$deletedIds) {
                    if ($key > 0) {
                        array_push($deletedIds, $item->id);
                    }
                });
            }
            $dataCount = count($deletedIds);
            if ($dataCount > 0) {
                DB::table('cutting_inventories')
                ->whereIn('id', $deletedIds)
                ->delete();
            }
            
            $this->info("Deleted Data Count: $dataCount");
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }
}
