<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCuttingQtyInTotalProductionReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cutting-qty-in-total-production-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Cutting Qty In TotalProductionReport';

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
            $this->info("Execution started successfully!");
            $orderId = $this->ask('Enter Order Id: ');
            $counter = 0;
            if ($orderId) {
                $this->updateData($orderId, $counter);
            } else {
                $date = now()->subDays(40)->toDateString();
                $orderIds = DB::table('bundle_cards')
                    ->where('status', 1)
                    ->where('cutting_date', '>=', $date)
                    ->whereNull('deleted_at')
                    ->groupBy('order_id')
                    ->pluck('order_id');
                foreach ($orderIds as $orderId) {
                    $this->updateData($orderId, $counter);
                }
            }
            
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }

    private function updateData($orderId, &$counter)
    {
        $bundleCardData = DB::table('bundle_cards')
            ->selectRaw('buyer_id, order_id, garments_item_id, purchase_order_id, color_id, factory_id, SUM(quantity) as total_cutting, sum(total_rejection) as total_cutting_rejection')
            ->where('status', 1)
            ->where('order_id', $orderId)
            ->whereNull('deleted_at')
            ->groupBy('buyer_id', 'order_id', 'garments_item_id', 'purchase_order_id', 'color_id', 'factory_id')
            ->get();

        foreach ($bundleCardData as $data) {
            DB::transaction(function () use ($data, &$counter) {
                $archivedData = DB::table('archived_bundle_cards')->selectRaw("SUM(quantity) as quantity, sum(total_rejection) as cutting_rejection")
                    ->where([
                        'purchase_order_id' => $data->purchase_order_id,
                        'color_id' => $data->color_id,
                        'factory_id' => $data->factory_id,
                        'status' => 1
                    ])
                    ->whereNull('deleted_at')
                    ->first();
                $archivedCuttingQty = $archivedData ? $archivedData->quantity : 0;
                $archivedCuttingRejection = $archivedData ? $archivedData->cutting_rejection : 0;
                $totalProductionReport = DB::table('total_production_reports')
                    ->where([
                        'buyer_id' => $data->buyer_id,
                        'order_id' => $data->order_id,
                        'garments_item_id' => $data->garments_item_id,
                        'purchase_order_id' => $data->purchase_order_id,
                        'color_id' => $data->color_id,
                        'factory_id' => $data->factory_id,
                    ])->first();
                if ($totalProductionReport) {
                    $id = $totalProductionReport->id;
                    $totalCutting = $data->total_cutting + $archivedCuttingQty;
                    $totalCuttingRejction = $data->total_cutting_rejection + $archivedCuttingRejection;
                    DB::table('total_production_reports')
                        ->where('id', $id)
                        ->update([
                            'total_cutting' => $totalCutting,
                            'total_cutting_rejection' => $totalCuttingRejction,
                        ]);
                    ++$counter;
                    $this->info("Updated Data: $counter");
                }
            });
        }
    }
}
