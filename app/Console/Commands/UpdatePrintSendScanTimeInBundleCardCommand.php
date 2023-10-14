<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdatePrintSendScanTimeInBundleCardCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:print-send-scan-time-in-bundle-card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Print Send Scan Time In BundleCard Command';

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
            DB::table('print_inventories')
                ->orderBy('id', 'desc')
                ->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $date)
                ->chunk($chunk_data_count, function ($print_inventories) use (&$counter) {
                    DB::beginTransaction();
                    foreach ($print_inventories as $print_inventory) {
                        $print_embr_send_scan_time = $print_inventory->created_at;
                        $bundle_card_id = $print_inventory->bundle_card_id;
                        $query = DB::table('bundle_cards')
                            ->where('id', $bundle_card_id);
                        if ($query->count()) {
                            $query
                                ->update([
                                    'print_embr_send_scan_time' => $print_embr_send_scan_time
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
