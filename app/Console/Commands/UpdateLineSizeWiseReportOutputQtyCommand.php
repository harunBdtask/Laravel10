<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateLineSizeWiseReportOutputQtyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:line-size-wise-report-output-qty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Line Size Wise Report Output Qty';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function updateData($data, $qty, $sewing_rejection)
    {
        return DB::transaction(function () use ($data, $qty, $sewing_rejection) {
            $report = DB::table('line_size_wise_sewing_reports')
                ->where($data)
                ->first();
            if (!$report) {
                $data['sewing_output'] = $qty;
                $data['sewing_rejection'] = $sewing_rejection;
                $data['created_at'] = \now();
                $data['updated_at'] = \now();
                DB::table('line_size_wise_sewing_reports')
                    ->insert($data);
            } else {
                $id = $report->id;
                $sewing_output = $report->sewing_output + $qty;
                $sewing_rejection = $report->sewing_rejection + $sewing_rejection;
                DB::table('line_size_wise_sewing_reports')
                    ->where('id', $id)
                    ->update([
                        'sewing_output' => $sewing_output,
                        'sewing_rejection' => $sewing_rejection
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
            DB::table('sewingoutputs')
                ->selectRaw('sewingoutputs.created_at as output_date_time, sewingoutputs.factory_id as factory_id, lines.id as line_id, lines.floor_id as floor_id, bundle_cards.buyer_id as buyer_id, bundle_cards.order_id as order_id, bundle_cards.purchase_order_id as purchase_order_id, 
                bundle_cards.color_id as color_id, bundle_cards.size_id as size_id, bundle_cards.quantity as quantity, bundle_cards.total_rejection as total_rejection, bundle_cards.print_rejection as print_rejection, bundle_cards.embroidary_rejection as embroidary_rejection, bundle_cards.sewing_rejection as sewing_rejection')
                ->join('bundle_cards', 'bundle_cards.id', 'sewingoutputs.bundle_card_id')
                ->leftJoin('lines', 'lines.id', 'sewingoutputs.line_id')
                ->where('bundle_cards.status', 1)
                ->whereNull('sewingoutputs.deleted_at')
                ->whereNull('bundle_cards.deleted_at')
                ->orderBy('sewingoutputs.id', 'asc')
                ->chunk($chunk_data_count, function ($sewingoutputs) use (&$counter) {
                    foreach ($sewingoutputs as $sewingoutput) {
                        $floor_id = $sewingoutput->floor_id;
                        $line_id = $sewingoutput->line_id;
                        $production_date = date('Y-m-d', strtotime($sewingoutput->output_date_time));
                        $factory_id = $sewingoutput->factory_id;
                        $buyer_id = $sewingoutput->buyer_id;
                        $order_id = $sewingoutput->order_id;
                        $purchase_order_id = $sewingoutput->purchase_order_id;
                        $color_id = $sewingoutput->color_id;
                        $size_id = $sewingoutput->size_id;
                        $sewing_rejection = $sewingoutput->sewing_rejection;
                        $bundleQty = $sewingoutput->quantity - $sewingoutput->total_rejection - $sewingoutput->print_rejection - $sewingoutput->embroidary_rejection - $sewing_rejection;
                        
                        $data = [
                            'production_date' => $production_date,
                            'floor_id' => $floor_id,
                            'line_id' => $line_id,
                            'buyer_id' => $buyer_id,
                            'order_id' => $order_id,
                            'purchase_order_id' => $purchase_order_id,
                            'color_id' => $color_id,
                            'size_id' => $size_id,
                            'factory_id' => $factory_id
                        ];
                        $exception = $this->updateData($data, $bundleQty, $sewing_rejection);
                        if (!\is_null($exception)) {
                            $this->info('Something went wrong!! ');
                            continue;
                        } else {
                            ++$counter;
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
