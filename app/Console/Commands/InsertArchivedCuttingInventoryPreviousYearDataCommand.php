<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertArchivedCuttingInventoryPreviousYearDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:archived-cutting-inventory-previous-year-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Archived Cutting Inventory Previous Year Data Command';

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
            $date = Carbon::now()->subDays(366)->toDateString();
            DB::table('cutting_inventories')
                ->orderBy('id', 'desc')
                ->whereDate('created_at', '<=', $date)
                ->chunk($chunk_data_count, function ($cutting_inventories) use (&$counter) {
                    DB::beginTransaction();
                    $data = $this->formatData($cutting_inventories);
                    if (count($data)) {
                        DB::table('archived_cutting_inventories')->insert($data);
                    }
                    DB::commit();
                    $counter += count($data);
                    $this->info("Archived Data count: " . $counter);
                });
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }

    private function formatData($cutting_inventories)
    {
        $data = [];
        foreach ($cutting_inventories as $cutting_inventory) {
            if (!DB::table('archived_cutting_inventories')->where('bundle_card_id', $cutting_inventory->bundle_card_id)->count()) {
                $this->info("Bundle card id: " . $cutting_inventory->bundle_card_id);
                $data[] = [
                    'id' => $cutting_inventory->id,
                    'challan_no' => $cutting_inventory->challan_no,
                    'bundle_card_id' => $cutting_inventory->bundle_card_id,
                    'status' => $cutting_inventory->status,
                    'print_status' => $cutting_inventory->print_status,
                    'factory_id' => $cutting_inventory->factory_id,
                    'created_by' => $cutting_inventory->created_by,
                    'deleted_by' => $cutting_inventory->deleted_by,
                    'deleted_at' => $cutting_inventory->deleted_at,
                    'created_at' => $cutting_inventory->created_at,
                    'updated_at' => $cutting_inventory->updated_at,
                ];
            }
        }

        return $data;
    }
}
