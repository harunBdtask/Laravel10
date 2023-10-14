<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertArchivedPrintInventoryPreviousYearDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:archived-print-inventory-previous-year-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Archived Print Inventory Previous Year Data Command';

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
            DB::table('print_inventories')
                ->orderBy('id', 'desc')
                ->whereDate('created_at', '<=', $date)
                ->chunk($chunk_data_count, function ($print_inventories) use (&$counter) {
                    DB::beginTransaction();
                    $data = $this->formatData($print_inventories);
                    if (count($data)) {
                        DB::table('archived_print_inventories')->insert($data);
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

    private function formatData($print_inventories)
    {
        $data = [];
        foreach ($print_inventories as $print_inventory) {
            if (!DB::table('archived_print_inventories')->where('id', $print_inventory->id)->count()) {
                $this->info("Bundle card id: " . $print_inventory->bundle_card_id);
                $data[] = [
                    'id' => $print_inventory->id,
                    'challan_no' => $print_inventory->challan_no,
                    'bundle_card_id' => $print_inventory->bundle_card_id,
                    'status' => $print_inventory->status,
                    'print_status' => $print_inventory->print_status,
                    'type' => $print_inventory->type,
                    'created_by' => $print_inventory->created_by,
                    'factory_id' => $print_inventory->factory_id,
                    'deleted_at' => $print_inventory->deleted_at,
                    'created_at' => $print_inventory->created_at,
                    'updated_at' => $print_inventory->updated_at,
                ];
            }
        }

        return $data;
    }
}
