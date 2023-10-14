<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateLineSizeWiseReportInputQtyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:line-size-wise-report-input-qty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Line Size Wise Report Input Qty';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function getCuttingInventories($challanNo)
    {
        return DB::table('cutting_inventories')
            ->join('bundle_cards', 'bundle_cards.id', 'cutting_inventories.bundle_card_id')
            ->selectRaw('bundle_cards.buyer_id as buyer_id, bundle_cards.order_id as order_id, bundle_cards.purchase_order_id as purchase_order_id, bundle_cards.color_id as color_id, bundle_cards.size_id as size_id, bundle_cards.quantity as quantity, bundle_cards.total_rejection as total_rejection, bundle_cards.print_rejection as print_rejection, bundle_cards.embroidary_rejection as embroidary_rejection')
            ->where('cutting_inventories.challan_no', $challanNo)
            ->where('bundle_cards.status', 1)
            ->whereNull('cutting_inventories.deleted_at')
            ->whereNull('bundle_cards.deleted_at')
            ->get();
    }

    private function updateData($data, $qty)
    {
        return DB::transaction(function () use ($data, $qty) {
            $report = DB::table('line_size_wise_sewing_reports')
                ->where($data)
                ->first();
            if (!$report) {
                $data['sewing_input'] = $qty;
                $data['created_at'] = \now();
                $data['updated_at'] = \now();
                DB::table('line_size_wise_sewing_reports')
                    ->insert($data);
            } else {
                $id = $report->id;
                $sewing_input = $report->sewing_input + $qty;
                DB::table('line_size_wise_sewing_reports')
                    ->where('id', $id)
                    ->update([
                        'sewing_input' => $sewing_input
                    ]);
            }
        });
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $counter = 0;
            $chunk_data_count = 500;
            $this->info("Execution started successfully!");
            DB::table('line_size_wise_sewing_reports')->truncate();
            DB::table('cutting_inventory_challans')
                ->selectRaw('cutting_inventory_challans.challan_no as challan_no, cutting_inventory_challans.input_date as input_date, lines.id as line_id, lines.floor_id as floor_id, cutting_inventory_challans.factory_id as factory_id')
                ->leftJoin('lines', 'lines.id', 'cutting_inventory_challans.line_id')
                ->where('cutting_inventory_challans.type', 'challan')
                ->whereNull('cutting_inventory_challans.deleted_at')
                ->orderBy('cutting_inventory_challans.id', 'asc')
                ->chunk($chunk_data_count, function ($cutting_inventory_challans) use (&$counter) {
                    foreach ($cutting_inventory_challans as $cutting_inventory_challan) {
                        $floor_id = $cutting_inventory_challan->floor_id;
                        $line_id = $cutting_inventory_challan->line_id;
                        $input_date = $cutting_inventory_challan->input_date;
                        $factory_id = $cutting_inventory_challan->factory_id;
                        $cutting_inventories = $this->getCuttingInventories($cutting_inventory_challan->challan_no);
                        foreach ($cutting_inventories as $cutting_inventory) {
                            $buyer_id = $cutting_inventory->buyer_id;
                            $order_id = $cutting_inventory->order_id;
                            $purchase_order_id = $cutting_inventory->purchase_order_id;
                            $color_id = $cutting_inventory->color_id;
                            $size_id = $cutting_inventory->size_id;
                            $bundleQty = $cutting_inventory->quantity - $cutting_inventory->total_rejection - $cutting_inventory->print_rejection - $cutting_inventory->embroidary_rejection;
                            $data = [
                                'production_date' => $input_date,
                                'floor_id' => $floor_id,
                                'line_id' => $line_id,
                                'buyer_id' => $buyer_id,
                                'order_id' => $order_id,
                                'purchase_order_id' => $purchase_order_id,
                                'color_id' => $color_id,
                                'size_id' => $size_id,
                                'factory_id' => $factory_id
                            ];
                            $exception = $this->updateData($data, $bundleQty);
                            if (!\is_null($exception)) {
                                $this->info('Something went wrong!! ');
                                continue;
                            } else {
                                ++$counter;
                            }
                        }
                        $this->info("Data count: " . $counter);
                    }
                    $this->info("Data count: " . $counter);
                });
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }
}
