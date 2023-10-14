<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateHourlyFinishingProductionInStyleAuditReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:hour-wise-finishing-data-in-style-audit-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updat Hourly Finishing Production In StyleAuditReport Model';

    const COLUMNS = [
        'poly',
        'iron',
        'packing',
    ];

    const REPORT_QTY_COLUMN_MAPPING = [
        'poly' => 'poly_qty',
        'iron' => 'iron_qty',
        'packing' => 'packing_qty',
    ];

    const REPORT_VALUE_COLUMN_MAPPING = [
        'poly' => 'poly_value',
        'iron' => 'iron_value',
        'packing' => 'packing_value',
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
            $date = now()->subDays(300)->toDateString();

            DB::table('hour_wise_finishing_productions')
                ->selectRaw('order_id as style_id, production_type, sum(CAST(hour_0 AS UNSIGNED) + CAST(hour_1 AS UNSIGNED) + CAST(hour_2 AS UNSIGNED) + CAST(hour_3 AS UNSIGNED) + CAST(hour_4 AS UNSIGNED) + CAST(hour_5 AS UNSIGNED) + CAST(hour_6 AS UNSIGNED) + CAST(hour_7 AS UNSIGNED) + CAST(hour_8 AS UNSIGNED) + CAST(hour_9 AS UNSIGNED) + CAST(hour_10 AS UNSIGNED) + CAST(hour_11 AS UNSIGNED) + CAST(hour_12 AS UNSIGNED) + CAST(hour_13 AS UNSIGNED) + CAST(hour_14 AS UNSIGNED) + CAST(hour_15 AS UNSIGNED) + CAST(hour_16 AS UNSIGNED) + CAST(hour_17 AS UNSIGNED) + CAST(hour_18 AS UNSIGNED) + CAST(hour_19 AS UNSIGNED) + CAST(hour_20 AS UNSIGNED) + CAST(hour_21 AS UNSIGNED) + CAST(hour_22 AS UNSIGNED) + CAST(hour_23 AS UNSIGNED)) as production_qty')
                ->where('production_date', '>=', $date)
                ->whereIn('production_type', self::COLUMNS)
                ->whereNull('deleted_at')
                ->groupBy('order_id', 'production_type')
                ->orderBy('order_id', 'desc')
                ->chunk($chunk_data_count, function ($finishingProductions) use (&$counter) {
                    foreach ($finishingProductions as $finishingProduction) {
                        $exception = DB::transaction(function () use ($finishingProduction) {
                            $qtyColumn = self::REPORT_QTY_COLUMN_MAPPING[$finishingProduction->production_type];
                            $valueColumn = self::REPORT_VALUE_COLUMN_MAPPING[$finishingProduction->production_type];
                            $productionQty = $finishingProduction->production_qty ?? 0;
                            $style_audit_reports = DB::table('style_audit_reports')
                                    ->where([
                                        'style_id' => $finishingProduction->style_id,
                                    ])->first();
                            if ($style_audit_reports) {
                                $id = $style_audit_reports->id;
                                $purchaseOrder = DB::table('purchase_orders')
                                    ->where('order_id', $finishingProduction->style_id)
                                    ->whereNull('deleted_at')
                                    ->orderBy('id', 'asc')
                                    ->first();
                                $fob_price = $purchaseOrder ? floatVal($purchaseOrder->avg_rate_pc_set) : 0;
                                $value = round(($productionQty * $fob_price), 4);
                                DB::table('style_audit_reports')
                                    ->where('id', $id)
                                    ->update([
                                        $qtyColumn => $productionQty,
                                        $valueColumn => $value,
                                    ]);
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
