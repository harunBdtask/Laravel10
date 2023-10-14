<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertArchivedBundleCardGenerationDetailDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:archived-bundlecard-generation-details-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Archived BundleCardGenerationDetail Data Command';

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
            DB::table('archived_bundle_cards')
                ->select('bundle_card_generation_detail_id')
                ->groupBy('bundle_card_generation_detail_id')
                ->orderBy('bundle_card_generation_detail_id', 'desc')
                ->chunk($chunk_data_count, function ($archived_bundle_cards) use (&$counter) {
                    DB::beginTransaction();
                    $data = $this->formatData($archived_bundle_cards);
                    if (count($data)) {
                        DB::table('archived_bundle_card_generation_details')->insert($data);
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

    private function formatData($archived_bundle_cards)
    {
        $data = [];
        foreach ($archived_bundle_cards as $bundle_card) {
            if (!DB::table('archived_bundle_card_generation_details')->where('sid', $bundle_card->bundle_card_generation_detail_id)->count()) {
                $this->info("SID: " . $bundle_card->bundle_card_generation_detail_id);
                DB::table('bundle_card_generation_details')
                    ->where('sid', $bundle_card->bundle_card_generation_detail_id)
                    ->get()
                    ->map(function ($item) use (&$data) {
                        $data[] = [
                            'id' => $item->id,
                            'sid' => $item->sid,
                            'is_regenerated' => $item->is_regenerated,
                            'max_quantity' => $item->max_quantity,
                            'booking_consumption' => $item->booking_consumption,
                            'booking_dia' => $item->booking_dia,
                            'booking_gsm' => $item->booking_gsm,
                            'cons_validation' => $item->cons_validation,
                            'buyer_id' => $item->buyer_id,
                            'order_id' => $item->order_id,
                            'garments_item_id' => $item->garments_item_id,
                            'colors' => $item->colors,
                            'cutting_no' => $item->cutting_no,
                            'cutting_floor_id' => $item->cutting_floor_id,
                            'cutting_table_id' => $item->cutting_table_id,
                            'is_tube' => $item->is_tube,
                            'part_id' => $item->part_id,
                            'type_id' => $item->type_id,
                            'lot_ranges' => $item->lot_ranges,
                            'rolls' => $item->rolls,
                            'ratios' => $item->ratios,
                            'is_manual' => $item->is_manual,
                            'po_details' => $item->po_details,
                            'created_by' => $item->created_by,
                            'updated_by' => $item->updated_by,
                            'deleted_by' => $item->deleted_by,
                            'factory_id' => $item->factory_id,
                            'size_suffix_sl_status' => $item->size_suffix_sl_status,
                            'deleted_at' => $item->deleted_at,
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                        ];
                    });
            }
        }

        return $data;
    }
}
