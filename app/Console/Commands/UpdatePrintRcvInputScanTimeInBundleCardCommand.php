<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdatePrintRcvInputScanTimeInBundleCardCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:print-rcv-input-scan-time-in-bundle-card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Print Rcv Input Scan Time In Bundle Card Command';

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
            $date = now()->subDays(2)->toDateString();
            DB::table('cutting_inventories')
                ->orderBy('id', 'desc')
                ->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $date)
                ->chunk($chunk_data_count, function ($cutting_inventories) use (&$counter) {
                    DB::beginTransaction();
                    foreach ($cutting_inventories as $cutting_inventory) {
                        $print_embr_received_scan_time = $cutting_inventory->print_status != 0 ? $cutting_inventory->created_at : null;
                        $input_scan_time = $cutting_inventory->created_at;
                        $bundle_card_id = $cutting_inventory->bundle_card_id;
                        $query = DB::table('bundle_cards')
                            ->where('id', $bundle_card_id);
                        if ($query->count()) {
                            $query
                                ->update([
                                    'print_embr_received_scan_time' => $print_embr_received_scan_time,
                                    'input_scan_time' => $input_scan_time,
                                ]);
                            ++$counter;
                        }
                    }
                    DB::commit();
                    $this->info("Data count: " . $counter);
                });
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }
}
