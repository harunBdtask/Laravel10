<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceiveReturn\YarnReceiveReturnDateWiseSummaryService;

class YarnStoreReceiveReturnStockFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock-fix:yarn-store-receive-return';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yarn store stock fix';

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
    public function handle(): int
    {
        try {
            $this->info('Fixing...!');
            DB::beginTransaction();

            /**
             * Fix Yarn Stock Tables
             */
            $this->yarnReceiveReturnFix();

            DB::commit();
            $this->info('Done!!!');
            return 1;
        } catch (\Throwable $e) {
            $this->info($e->getMessage());
            return 0;
        }
    }

    public function yarnReceiveReturnFix()
    {
        $yarnDateWiseSummaryCalculator = new YarnReceiveReturnDateWiseSummaryService();
        YarnReceiveReturnDetail::query()->with('receiveReturn')
            ->orderBy('id', 'ASC')->chunk(100, function ($yarnReceiveReturnDetails)
            use (&$yarnStockSummaryCalculator, &$yarnDateWiseSummaryCalculator) {
                foreach ($yarnReceiveReturnDetails as $key => $yarn) {

                    /*
                     * Date wise stock summary fix
                     * */
                    $yarnDateWiseStockSummary = $yarnDateWiseSummaryCalculator->summary($yarn);
                    if ($yarnDateWiseStockSummary) {
                        $yarnDateWiseSummaryCalculator->createSameItem($yarn, $yarnDateWiseStockSummary);
                    } else {
                        $yarnDateWiseSummaryCalculator->create($yarn, $yarn->receiveReturn->return_date);
                    }
                }
            });
    }
}
