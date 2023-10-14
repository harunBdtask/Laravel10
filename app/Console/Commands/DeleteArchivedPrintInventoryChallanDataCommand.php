<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteArchivedPrintInventoryChallanDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:archived-print-inventory-challan-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Archived PrintInventoryChallan Data Command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $counter = 0;
            $chunk_data_count = 500;
            $this->info("Execution started successfully!");
            DB::table('archived_print_inventory_challans')
                ->select('challan_no')
                ->orderBy('challan_no', 'desc')
                ->chunk($chunk_data_count, function ($data_details) use (&$counter) {
                    DB::beginTransaction();

                    $deleteable_challans = $this->getDeletableChallans($data_details);
                    if (count($deleteable_challans)) {
                        DB::table('print_inventory_challans')->whereIn('challan_no', $deleteable_challans)->delete();
                    }

                    DB::commit();
                    $counter += count($deleteable_challans);
                    $this->info("Deleted Data count: " . $counter);
                });
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }

    private function getDeletableChallans($data_details) : array
    {
        $data = [];
        foreach ($data_details as $detail) {
            if (!DB::table('print_inventories')->where('challan_no', $detail->challan_no)->count()) {
                $this->info("Challan No: " . $detail->challan_no);
                $data[] = $detail->challan_no;
            }
        }
        return $data;
    }
}
