<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationCache;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;

class BundleCardRecover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bundle-card:recover';

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
        $this->formatBundleCardGenerationDetails();
        $this->generateBundleCards();
    }

    private function formatBundleCardGenerationDetails()
    {
        // Last SID = 16482
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::beginTransaction();
            BundleCardGenerationCache::query()
                ->where('sid', '>', 16482)
                ->chunk(10, function ($collection) {
                    $bundleCardGenerationDetails = [];
                    foreach ($collection as $item) {
                        $details = $item->details['bundleCardGenerationDetail'];
                        $bundleCardGenerationDetails[] = [
                            'id' => $details['id'],
                            'sid' => $details['sid'],
                            'is_regenerated' => $details['is_regenerated'],
                            'max_quantity' => $details['max_quantity'],
                            'booking_consumption' => $details['booking_consumption'],
                            'booking_dia' => $details['booking_dia'],
                            'buyer_id' => $details['buyer_id'],
                            'order_id' => $details['order_id'],
                            'garments_item_id' => $details['garments_item_id'],
                            'colors' => $details['colors'],
                            'cutting_no' => $details['cutting_no'],
                            'cutting_floor_id' => $details['cutting_floor_id'],
                            'cutting_table_id' => $details['cutting_table_id'],
                            'is_tube' => $details['is_tube'],
                            'part_id' => $details['part_id'],
                            'type_id' => $details['type_id'],
                            'lot_ranges' => json_encode($details['lot_ranges']),
                            'rolls' => json_encode($details['rolls']),
                            'ratios' => json_encode($details['ratios']),
                            'is_manual' => $details['is_manual'],
                            'po_details' => json_encode($details['po_details']),
                            'created_at' => $details['created_at'],
                            'updated_at' => $details['updated_at'],
                            'created_by' => $details['created_by'],
                            'updated_by' => $details['updated_by'],
                            'deleted_by' => $details['deleted_by'],
                            'factory_id' => $details['factory_id'],
                        ];
                    }

                    BundleCardGenerationDetail::query()->insert($bundleCardGenerationDetails);
                    $this->info('Bundlecard Generation data updated');
                });
            DB::commit();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
            $this->info($e->getTrace());
        }
    }

    private function generateBundleCards()
    {
        // Last SID = 16482
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            BundleCardGenerationCache::query()
                ->where('sid', '>', 16482)
                ->whereColumn('sid', 'bg_id')
                ->chunk(10, function ($collection) {
                    foreach ($collection as $item) {
                        $bundleCardDetails = [];
                        $bundleCards = $item->details['bundleCards'];
                        
                        foreach ($bundleCards as $bundleCard) {
                            $bundleCardGeneration = $item->details['bundleCardGenerationDetail'];
                            $bundleCardDetails[] = [
                                'id' => $bundleCard['id'],
                                'bundle_no' => $bundleCard['bundle_no'],
                                'size_wise_bundle_no' => $bundleCard['size_wise_bundle_no'],
                                'quantity' => $bundleCard['quantity'],
                                'buyer_id' => $bundleCard['buyer_id'],
                                'order_id' => $bundleCard['order_id'],
                                'garments_item_id' => $bundleCardGeneration['garments_item_id'],
                                'purchase_order_id' => $bundleCard['purchase_order_id'],
                                'color_id' => $bundleCard['color_id'],
                                'country_id' => $bundleCard['country_id'],
                                'lot_id' => $bundleCard['lot_id'],
                                'roll_no' => $bundleCard['roll_no'],
                                'size_id' => $bundleCard['size_id'],
                                'suffix' => $bundleCard['suffix'],
                                'serial' => $bundleCard['serial'],
                                'sl_overflow' => $bundleCard['sl_overflow'],
                                'cutting_no' => $bundleCard['cutting_no'],
                                'cutting_challan_no' => null,
                                'cutting_challan_status' => 0,
                                'cutting_qc_challan_no' => null,
                                'cutting_qc_challan_status' => 0,
                                'bundle_card_generation_detail_id' => $bundleCardGeneration['id'],
                                'replace' => null,
                                'fabric_holes_small' => null,
                                'fabric_holes_large' => null,
                                'end_out' => null,
                                'dirty_spot' => null,
                                'oil_spot' => null,
                                'colour_spot' => null,
                                'lycra_missing' => null,
                                'missing_yarn' => null,
                                'yarn_contamination' => null,
                                'crease_mark' => null,
                                'others' => null,
                                'total_rejection' => 0,
                                'production_rejection_qty' => 0,
                                'qc_rejection_qty' => 0,
                                'print_rejection' => 0,
                                'embroidary_rejection' => 0,
                                'sewing_rejection' => 0,
                                'washing_rejection' => 0,
                                'print_factory_receive_rejection' => 0,
                                'print_factory_delivery_rejection' => 0,
                                'status' => 1,
                                'qc_status' => 0,
                                'cutting_date' => Carbon::parse($bundleCardGeneration['created_at'])->format('Y-m-d'),
                                'print_sent_date' => null,
                                'print_received_date' => null,
                                'embroidary_sent_date' => null,
                                'embroidary_received_date' => null,
                                'input_date' => null,
                                'sewing_output_date' => null,
                                'washing_date' => null,
                                'cutting_table_id' => $bundleCardGeneration['cutting_table_id'],
                                'cutting_floor_id' => $bundleCardGeneration['cutting_floor_id'],
                                'factory_id' => $bundleCardGeneration['factory_id'],
                                'created_at' => $bundleCardGeneration['created_at'],
                                'updated_at' => $bundleCardGeneration['updated_at'],
                                'created_by' => $bundleCardGeneration['created_by'],
                                'updated_by' => $bundleCardGeneration['updated_by'],
                                'deleted_by' => $bundleCardGeneration['deleted_by'],
                            ];
                        }
                        BundleCard::query()->insert($bundleCardDetails);
                        $this->info('Bundlecard data updated');
                    }
                });
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (Exception $e) {
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
            $this->info($e->getTrace());
        }
    }
}
