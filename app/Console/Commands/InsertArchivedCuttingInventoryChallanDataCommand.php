<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertArchivedCuttingInventoryChallanDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:archived-cutting-inventory-challan-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Archived Cutting Inventory Challan Data Command';

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
            DB::table('archived_cutting_inventories')
                ->select('challan_no')
                ->groupBy('challan_no')
                ->orderBy('challan_no', 'desc')
                ->chunk($chunk_data_count, function ($archived_cutting_inventories) use (&$counter) {
                    DB::beginTransaction();
                    $data = $this->formatData($archived_cutting_inventories);
                    if (count($data)) {
                        DB::table('archived_cutting_inventory_challans')->insert($data);
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

    private function formatData($archived_cutting_inventories)
    {
        $data = [];
        foreach ($archived_cutting_inventories as $cutting_inventoy) {
            if (!DB::table('archived_cutting_inventory_challans')->where('challan_no', $cutting_inventoy->challan_no)->count()) {
                $this->info("Challan No: " . $cutting_inventoy->challan_no);
                DB::table('cutting_inventory_challans')->where('challan_no', $cutting_inventoy->challan_no)
                    ->get()
                    ->map(function ($item) use (&$data) {
                        $data[] = [
                            'id' => $item->id,
                            'challan_no' => $item->challan_no,
                            'status' => $item->status,
                            'line_id' => $item->line_id,
                            'type' => $item->type,
                            'print_status' => $item->print_status,
                            'input_date' => $item->input_date,
                            'color_id' => $item->color_id,
                            'factory_id' => $item->factory_id,
                            'total_rib_size' => $item->total_rib_size,
                            'rib_comments' => $item->rib_comments,
                            'created_by' => $item->created_by,
                            'updated_by' => $item->updated_by,
                            'deleted_by' => $item->deleted_by,
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                            'deleted_at' => $item->deleted_at,
                        ];
                    });
            }
        }

        return $data;
    }
}
