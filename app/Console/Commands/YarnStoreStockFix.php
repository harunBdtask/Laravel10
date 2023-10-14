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

class YarnStoreStockFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock-fix:yarn-store';

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
             * Truncate Yarn Stock Tables
             */
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('yarn_stock_summaries')->truncate();
            DB::table('yarn_date_wise_stock_summaries')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');


            /**
             * Fix Yarn Stock Tables
             */
            $this->yarnReceiveFix();
            $this->yarnIssueFix();

            DB::commit();
            $this->info('Done!!!');
            return 1;
        } catch (\Throwable $e) {
            $this->info($e->getMessage());
            return 0;
        }
    }

    public function yarnReceiveFix()
    {
        $yarnStockSummaryCalculator = new YarnStockSummaryCalculator();
        $yarnDateWiseSummaryCalculator = new YarnDateWiseSummaryCalculator();
        YarnReceiveDetail::query()->with('yarnReceive')
            ->orderBy('id', 'ASC')->chunk(100, function ($yarnReceiveDetails)
            use (&$yarnStockSummaryCalculator, &$yarnDateWiseSummaryCalculator) {
                foreach ($yarnReceiveDetails as $key => $yarn) {

                    /*
                     * Stock summary fix
                     * */
                    $yarnStockSummary = $yarnStockSummaryCalculator->summary($yarn);
                    if ($yarnStockSummary) {
                        $yarnStockSummaryCalculator->createSameItem($yarn, $yarnStockSummary);
                    } else {
                        $yarnStockSummaryCalculator->create($yarn);
                    }

                    /*
                     * Date wise stock summary fix
                     * */
                    $yarnDateWiseStockSummary = $yarnDateWiseSummaryCalculator->summary($yarn);
                    if ($yarnDateWiseStockSummary) {
                        $yarnDateWiseSummaryCalculator->createSameItem($yarn, $yarnDateWiseStockSummary);
                    } else {
                        $yarnDateWiseSummaryCalculator->create($yarn, $yarn->yarnReceive->receive_date);
                    }
                }
            });
    }

    public function yarnIssueFix()
    {
        $yarnIssueStockSummaryService = new YarnIssueStockSummaryService();
        $yarnIssueDateWiseSummaryService = new YarnIssueDateWiseSummaryService();
        YarnIssueDetail::query()->with('issue')
            ->orderBy('id', 'ASC')->chunk(100, function ($yarnIssueDetails)
            use (&$yarnIssueStockSummaryService, &$yarnIssueDateWiseSummaryService) {
                foreach ($yarnIssueDetails as $key => $yarn) {

                    /*
                     * Stock summary fix
                     * */
                    $yarnStockSummary = $yarnIssueStockSummaryService->summary($yarn);
                    if ($yarnStockSummary) {
                        $yarnIssueStockSummaryService->createSameItem($yarn, $yarnStockSummary);
                    }

                    /*
                     * Date wise stock summary fix
                     * */
                    $yarnDateWiseStockSummary = $yarnIssueDateWiseSummaryService->summary($yarn);
                    if ($yarnDateWiseStockSummary) {
                        $yarnIssueDateWiseSummaryService->createSameItem($yarn, $yarnDateWiseStockSummary);
                    } else {
                        $yarnIssueDateWiseSummaryService->create($yarn, $yarn->issue->issue_date);
                    }
                }
            });
    }
}
