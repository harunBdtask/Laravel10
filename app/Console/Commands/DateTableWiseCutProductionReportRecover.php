<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;

class DateTableWiseCutProductionReportRecover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'date-table-wise-cut-production-report:recover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            DB::beginTransaction();
            $counter = 0;
            $this->info("Command Initialized");
            DB::table('bundle_cards')
                ->where('bundle_card_generation_detail_id', '>', 16482)
                ->where('bundle_card_generation_detail_id', '<=', 19672)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'asc')
                ->chunk(10, function ($bundleCards) use (&$counter) {
                    foreach ($bundleCards as $bundleCard) {
                        $this->updateDateTableWiseCutProductionReport($bundleCard);
                        $this->info("Counter: " . ++$counter);
                    }
                });
            $this->info("Command Executed Successfully");
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
            $this->info($e->getTrace());
        }
    }

    private function updateDateTableWiseCutProductionReport($bundleCard)
    {
        $orderId = $bundleCard->order_id;
        $garmentsItemId = $bundleCard->garments_item_id;
        $purchaseOrderId = $bundleCard->purchase_order_id;
        $colorId = $bundleCard->color_id;

        $dateTableWiseCutProductionReports = DB::table('date_table_wise_cut_production_reports')->where([
            'production_date' => $bundleCard->cutting_date,
            'cutting_table_id' => $bundleCard->cutting_table_id,
            'order_id' => $orderId,
            'garments_item_id' => $garmentsItemId,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $bundleCard->size_id,
            'factory_id' => $bundleCard->factory_id,
        ])->first();

        if (!$dateTableWiseCutProductionReports) {
            $reportData = [
                'production_date' => $bundleCard->cutting_date,
                'cutting_floor_id' => $bundleCard->cutting_floor_id,
                'cutting_table_id' => $bundleCard->cutting_table_id,
                'buyer_id' => $bundleCard->buyer_id,
                'order_id' => $orderId,
                'garments_item_id' => $garmentsItemId,
                'purchase_order_id' => $purchaseOrderId,
                'color_id' => $colorId,
                'size_id' => $bundleCard->size_id,
                'factory_id' => $bundleCard->factory_id,
                'cutting_qty' => $bundleCard->quantity,
                'cutting_rejection_qty' => $bundleCard->total_rejection,
            ];

            DB::table('date_table_wise_cut_production_reports')->insert($reportData);
        } else {
            $total_cutting = $dateTableWiseCutProductionReports->cutting_qty + $bundleCard->quantity;
            $total_cutting_rejection = $dateTableWiseCutProductionReports->cutting_rejection_qty + $bundleCard->total_rejection;
            DB::table('date_table_wise_cut_production_reports')
                ->where('id', $dateTableWiseCutProductionReports->id)
                ->update([
                    'cutting_qty' => $total_cutting,
                    'cutting_rejection_qty' => $total_cutting_rejection,
                ]);
        }
    }
}
