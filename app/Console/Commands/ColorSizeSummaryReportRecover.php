<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\ColorSizeSummaryReport;

class ColorSizeSummaryReportRecover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'color-size-summary-report:recover';

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
                        $this->updateColorSizeSummaryReport($bundleCard);
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

    private function updateColorSizeSummaryReport($bundleCard)
    {
        $colorSizeSummaryReport = DB::table('color_size_summary_reports')
            ->where([
                'purchase_order_id' => $bundleCard->purchase_order_id,
                'color_id' => $bundleCard->color_id,
                'size_id' => $bundleCard->size_id,
                'factory_id' => $bundleCard->factory_id,
            ])->first();

        if (!$colorSizeSummaryReport) {
            $summaryReportData = [
                'buyer_id' => $bundleCard->buyer_id,
                'order_id' => $bundleCard->order_id,
                'purchase_order_id' => $bundleCard->purchase_order_id,
                'color_id' => $bundleCard->color_id,
                'size_id' => $bundleCard->size_id,
                'factory_id' => $bundleCard->factory_id,
                'total_cutting' => $bundleCard->quantity,
                'total_cutting_rejection' => $bundleCard->total_rejection,
            ];
            DB::table('color_size_summary_reports')->insert($summaryReportData);
        } else {
            $total_cutting = $colorSizeSummaryReport->total_cutting + $bundleCard->quantity;
            $total_cutting_rejection = $colorSizeSummaryReport->total_cutting_rejection + $bundleCard->total_rejection;
            DB::table('color_size_summary_reports')
                ->where('id', $colorSizeSummaryReport->id)
                ->update([
                    'total_cutting' => $total_cutting,
                    'total_cutting_rejection' => $total_cutting_rejection,
                ]);
        }
    }
}
