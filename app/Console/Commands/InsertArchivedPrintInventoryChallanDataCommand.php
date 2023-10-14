<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertArchivedPrintInventoryChallanDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:archived-print-inventory-challan-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Archived Print Inventory Challan Data Command';

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
            DB::table('archived_print_inventories')
                ->select('challan_no')
                ->groupBy('challan_no')
                ->orderBy('challan_no', 'desc')
                ->chunk($chunk_data_count, function ($archived_print_inventories) use (&$counter) {
                    DB::beginTransaction();
                    $data = $this->formatData($archived_print_inventories);
                    if (count($data)) {
                        DB::table('archived_print_inventory_challans')->insert($data);
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

    private function formatData($archived_print_inventories)
    {
        $data = [];
        foreach ($archived_print_inventories as $print_inventory) {
            if (!DB::table('archived_print_inventory_challans')->where('challan_no', $print_inventory->challan_no)->count()) {
                $this->info("Challan No: " . $print_inventory->challan_no);
                DB::table('print_inventory_challans')
                    ->where('challan_no', $print_inventory->challan_no)
                    ->get()
                    ->map(function ($item) use (&$data) {
                        $data[] = [
                            'id' => $item->id,
                            'challan_no' => $item->challan_no,
                            'status' => $item->status,
                            'bag' => $item->bag,
                            'operation_name' => $item->operation_name,
                            'part_id' => $item->part_id,
                            'send_total_qty' => $item->send_total_qty,
                            'print_factory_id' => $item->print_factory_id,
                            'security_status' => $item->security_status,
                            'cut_manager_approval_steps' => $item->cut_manager_approval_steps,
                            'cut_manager_approval_status' => $item->cut_manager_approval_status,
                            'cut_manager_approved_id' => $item->cut_manager_approved_id,
                            'factory_id' => $item->factory_id,
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
