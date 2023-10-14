<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertArchivedBundleCardDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:archived-bundle-card-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Archived BundleCard Data Command';


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
            $date = Carbon::now()->subDays(180)->toDateString();
            DB::table('sewingoutputs')
                ->select('bundle_card_id')
                ->orderBy('id', 'desc')
                ->whereDate('created_at', '<=', $date)
                ->chunk($chunk_data_count, function ($sewingoutputs) use (&$counter) {
                    DB::beginTransaction();
                    $bundle_cards = DB::table('bundle_cards')->whereIn('id', $sewingoutputs->pluck('bundle_card_id')->toArray())->get();
                    $data = $this->formatData($bundle_cards);
                    if (count($data)) {
                        DB::table('archived_bundle_cards')->insert($data);
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

    private function formatData($bundle_cards)
    {
        $data = [];
        foreach ($bundle_cards as $bundle_card) {
            if (!DB::table('archived_bundle_cards')->where('id', $bundle_card->id)->count()) {
                $this->info("Bundle card id: " . $bundle_card->id);
                $data[] = [
                    'id' => $bundle_card->id,
                    'bundle_no' => $bundle_card->bundle_no,
                    'size_wise_bundle_no' => $bundle_card->size_wise_bundle_no,
                    'quantity' => $bundle_card->quantity,
                    'buyer_id' => $bundle_card->buyer_id,
                    'order_id' => $bundle_card->order_id,
                    'garments_item_id' => $bundle_card->garments_item_id,
                    'purchase_order_id' => $bundle_card->purchase_order_id,
                    'color_id' => $bundle_card->color_id,
                    'country_id' => $bundle_card->country_id,
                    'lot_id' => $bundle_card->lot_id,
                    'roll_no' => $bundle_card->roll_no,
                    'size_id' => $bundle_card->size_id,
                    'suffix' => $bundle_card->suffix,
                    'serial' => $bundle_card->serial,
                    'sl_overflow' => $bundle_card->sl_overflow,
                    'cutting_no' => $bundle_card->cutting_no,
                    'cutting_challan_no' => $bundle_card->cutting_challan_no,
                    'cutting_challan_status' => $bundle_card->cutting_challan_status,
                    'cutting_qc_challan_no' => $bundle_card->cutting_qc_challan_no,
                    'cutting_qc_challan_status' => $bundle_card->cutting_qc_challan_status,
                    'bundle_card_generation_detail_id' => $bundle_card->bundle_card_generation_detail_id,
                    'replace' => $bundle_card->replace,
                    'fabric_holes_small' => $bundle_card->fabric_holes_small,
                    'fabric_holes_large' => $bundle_card->fabric_holes_large,
                    'end_out' => $bundle_card->end_out,
                    'dirty_spot' => $bundle_card->dirty_spot,
                    'oil_spot' => $bundle_card->oil_spot,
                    'colour_spot' => $bundle_card->colour_spot,
                    'lycra_missing' => $bundle_card->lycra_missing,
                    'missing_yarn' => $bundle_card->missing_yarn,
                    'yarn_contamination' => $bundle_card->yarn_contamination,
                    'crease_mark' => $bundle_card->crease_mark,
                    'others' => $bundle_card->others,
                    'total_rejection' => $bundle_card->total_rejection,
                    'production_rejection_qty' => $bundle_card->production_rejection_qty,
                    'qc_rejection_qty' => $bundle_card->qc_rejection_qty,
                    'print_rejection' => $bundle_card->print_rejection,
                    'embroidary_rejection' => $bundle_card->embroidary_rejection,
                    'sewing_rejection' => $bundle_card->sewing_rejection,
                    'washing_rejection' => $bundle_card->washing_rejection,
                    'print_factory_receive_rejection' => $bundle_card->print_factory_receive_rejection,
                    'print_factory_delivery_rejection' => $bundle_card->print_factory_delivery_rejection,
                    'status' => $bundle_card->status,
                    'qc_status' => $bundle_card->qc_status,
                    'cutting_date' => $bundle_card->cutting_date,
                    'print_embr_send_scan_time' => $bundle_card->print_embr_send_scan_time,
                    'print_sent_date' => $bundle_card->print_sent_date,
                    'print_embr_received_scan_time' => $bundle_card->print_embr_received_scan_time,
                    'print_received_date' => $bundle_card->print_received_date,
                    'embroidary_sent_date' => $bundle_card->embroidary_sent_date,
                    'embroidary_received_date' => $bundle_card->embroidary_received_date,
                    'input_scan_time' => $bundle_card->input_scan_time,
                    'input_date' => $bundle_card->input_date,
                    'sewing_output_date' => $bundle_card->sewing_output_date,
                    'washing_date' => $bundle_card->washing_date,
                    'cutting_table_id' => $bundle_card->cutting_table_id,
                    'cutting_floor_id' => $bundle_card->cutting_floor_id,
                    'factory_id' => $bundle_card->factory_id,
                    'created_by' => $bundle_card->created_by,
                    'updated_by' => $bundle_card->updated_by,
                    'deleted_by' => $bundle_card->deleted_by,
                    'created_at' => $bundle_card->created_at,
                    'updated_at' => $bundle_card->updated_at,
                    'deleted_at' => $bundle_card->deleted_at,
                ];
            }
        }

        return $data;
    }
}
