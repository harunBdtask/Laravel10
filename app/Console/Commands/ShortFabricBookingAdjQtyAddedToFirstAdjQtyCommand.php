<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryCalculator;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryCalculator;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetailsBreakdown;
use Throwable;

class ShortFabricBookingAdjQtyAddedToFirstAdjQtyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'short-fab-booking:adj-qty-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Short Fabric Booking Adj Qty Added To First Adj Qty Command';

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
     * @throws Throwable
     */
    public function handle(): int
    {
        try {
            $this->info('Fixing...!');

            DB::beginTransaction();

            $counter = 0;
            ShortFabricBookingDetailsBreakdown::query()->orderBy('id', 'asc')
                ->where('adj_qty_status', 0)
                ->whereNotNull('adj_qty')
                ->chunk(500, function ($shortFabBooking) use (&$counter){
                    foreach ($shortFabBooking as $data) {
                        if ($data->adj_qty) {
                            $data->update([
                                'first_adj_qty' => $data->adj_qty,
                                'adj_qty_status' => 1
                            ]);
                            $counter++;
                        }
                    }
                });

            DB::commit();

            $this->info("$counter Rows Updated Successfully! :D");
            return 1;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->info($e->getMessage());
            $this->info($e->getLine());
            return 0;
        }
    }
}
