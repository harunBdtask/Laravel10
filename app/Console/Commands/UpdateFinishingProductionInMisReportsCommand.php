<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateFinishingProductionInMisReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:finishing-production-in-mis-reports {from_date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Finishing Production In MIS Reports Command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const COLUMNS = [
        'poly', 
        'poly_rejection', 
        'iron', 
        'iron_rejection', 
        'packing', 
        'packing_rejection'
    ];

    const COLUMN_MAPPING = [
        'poly' => 'poly_qty',
        'poly_rejection' => 'poly_rejection',
        'iron' => 'iron_qty', 
        'iron_rejection' => 'iron_rejection_qty', 
        'packing' => 'packing_qty', 
        'packing_rejection' => 'packing_rejection_qty'
    ];

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
            $from_date = $this->argument('from_date');

            DB::table('hour_wise_finishing_productions')
                ->selectRaw('production_date, buyer_id, order_id, po_id AS purchase_order_id, color_id, factory_id, production_type, sum(CAST(hour_0 AS UNSIGNED) + CAST(hour_1 AS UNSIGNED) + CAST(hour_2 AS UNSIGNED) + CAST(hour_3 AS UNSIGNED) + CAST(hour_4 AS UNSIGNED) + CAST(hour_5 AS UNSIGNED) + CAST(hour_6 AS UNSIGNED) + CAST(hour_7 AS UNSIGNED) + CAST(hour_8 AS UNSIGNED) + CAST(hour_9 AS UNSIGNED) + CAST(hour_10 AS UNSIGNED) + CAST(hour_11 AS UNSIGNED) + CAST(hour_12 AS UNSIGNED) + CAST(hour_13 AS UNSIGNED) + CAST(hour_14 AS UNSIGNED) + CAST(hour_15 AS UNSIGNED) + CAST(hour_16 AS UNSIGNED) + CAST(hour_17 AS UNSIGNED) + CAST(hour_18 AS UNSIGNED) + CAST(hour_19 AS UNSIGNED) + CAST(hour_20 AS UNSIGNED) + CAST(hour_21 AS UNSIGNED) + CAST(hour_22 AS UNSIGNED) + CAST(hour_23 AS UNSIGNED)) as production_qty')
                ->when($from_date, function($query) use($from_date) {
                    $query->where('production_date', '>=', $from_date);
                })
                ->whereIn('production_type', self::COLUMNS)
                ->whereNull('deleted_at')
                ->groupBy('production_date', 'buyer_id', 'order_id', 'po_id', 'color_id', 'production_type', 'factory_id')
                ->orderBy('production_date', 'desc')
                ->chunk($chunk_data_count, function ($finishingProductions) use (&$counter) {
                    foreach ($finishingProductions as $finishingProduction) {
                        $exception = DB::transaction(function () use ($finishingProduction) {
                            $column = self::COLUMN_MAPPING[$finishingProduction->production_type];
                            $productionQty = $finishingProduction->production_qty ?? 0;
                            $date_and_color_wise_productions = DB::table('date_and_color_wise_productions')
                                ->where([
                                    'production_date' => $finishingProduction->production_date,
                                    'purchase_order_id' => $finishingProduction->purchase_order_id,
                                    'color_id' => $finishingProduction->color_id,
                                    'factory_id' => $finishingProduction->factory_id,
                                ])->first();
                                if (!$date_and_color_wise_productions) {
                                    $timestamp = Carbon::parse($finishingProduction->production_date)->endOfDay()->toDateTimeString();
                                    DB::table('date_and_color_wise_productions')
                                        ->insert([
                                            'production_date' => $finishingProduction->production_date,
                                            'buyer_id' => $finishingProduction->buyer_id,
                                            'order_id' => $finishingProduction->order_id,
                                            'purchase_order_id' => $finishingProduction->purchase_order_id,
                                            'color_id' => $finishingProduction->color_id,
                                            'factory_id' => $finishingProduction->factory_id,
                                            $column => $productionQty,
                                            'created_at' => $timestamp,
                                            'updated_at' => $timestamp,
                                        ]);
                                } else {
                                    $id = $date_and_color_wise_productions->id;
                                    DB::table('date_and_color_wise_productions')
                                        ->where('id', $id)
                                        ->update([
                                            $column => $productionQty,
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
