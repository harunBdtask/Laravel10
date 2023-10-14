<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateTodayHourlyFinishingProductionDataInTotalProductionReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:today-hour-wise-finishing-data-in-total-production-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Todays Hourly Finishing Production Data In TotalProductionReport Model';

    const COLUMNS = [
        'poly',
        'poly_rejection',
        'iron',
        'iron_rejection',
        'packing',
        'packing_rejection'
    ];

    const COLUMN_MAPPING = [
        'poly' => 'todays_poly',
        'poly_rejection' => 'todays_poly_rejection',
        'iron' => 'todays_iron',
        'iron_rejection' => 'todays_iron_rejection',
        'packing' => 'todays_packing',
        'packing_rejection' => 'todays_packing_rejection'
    ];

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
            $counter = 0;
            $chunk_data_count = 500;
            $this->info("Execution started successfully!");
            $date = now()->toDateString();

            DB::table('hour_wise_finishing_productions')
                ->selectRaw('buyer_id, order_id, item_id as garments_item_id, po_id AS purchase_order_id, color_id, factory_id, production_type, sum(CAST(hour_0 AS UNSIGNED) + CAST(hour_1 AS UNSIGNED) + CAST(hour_2 AS UNSIGNED) + CAST(hour_3 AS UNSIGNED) + CAST(hour_4 AS UNSIGNED) + CAST(hour_5 AS UNSIGNED) + CAST(hour_6 AS UNSIGNED) + CAST(hour_7 AS UNSIGNED) + CAST(hour_8 AS UNSIGNED) + CAST(hour_9 AS UNSIGNED) + CAST(hour_10 AS UNSIGNED) + CAST(hour_11 AS UNSIGNED) + CAST(hour_12 AS UNSIGNED) + CAST(hour_13 AS UNSIGNED) + CAST(hour_14 AS UNSIGNED) + CAST(hour_15 AS UNSIGNED) + CAST(hour_16 AS UNSIGNED) + CAST(hour_17 AS UNSIGNED) + CAST(hour_18 AS UNSIGNED) + CAST(hour_19 AS UNSIGNED) + CAST(hour_20 AS UNSIGNED) + CAST(hour_21 AS UNSIGNED) + CAST(hour_22 AS UNSIGNED) + CAST(hour_23 AS UNSIGNED)) as production_qty')
                ->where('production_date', $date)
                ->whereIn('production_type', self::COLUMNS)
                ->whereNull('deleted_at')
                ->groupBy('buyer_id', 'order_id', 'item_id', 'po_id', 'color_id', 'production_type', 'factory_id')
                ->orderBy('buyer_id', 'desc')
                ->chunk($chunk_data_count, function ($finishingProductions) use (&$counter) {
                    foreach ($finishingProductions as $finishingProduction) {
                        $exception = DB::transaction(function () use ($finishingProduction) {
                            $columnName = self::COLUMN_MAPPING[$finishingProduction->production_type];
                            $productionQty = $finishingProduction->production_qty ?? 0;
                            if ($productionQty > 0) {
                                $total_production_reports = DB::table('total_production_reports')
                                    ->where([
                                        'garments_item_id' => $finishingProduction->garments_item_id,
                                        'purchase_order_id' => $finishingProduction->purchase_order_id,
                                        'color_id' => $finishingProduction->color_id,
                                        'factory_id' => $finishingProduction->factory_id,
                                    ])->first();
                                if ($total_production_reports) {
                                    $id = $total_production_reports->id;
                                    DB::table('total_production_reports')
                                        ->where('id', $id)
                                        ->update([
                                            $columnName => $productionQty
                                        ]);
                                }
                            }
                        });
                        if (!\is_null($exception)) {
                            $this->info('Something went wrong!! ');
                            continue;
                        } else {
                            ++$counter;
                        }
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
