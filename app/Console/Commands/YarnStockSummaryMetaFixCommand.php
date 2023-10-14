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

class YarnStockSummaryMetaFixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock-fix:yarn-stock-summary-meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yarn stock summary meta fix';

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

            $counter = 0;

            DB::table('yarn_stock_summaries')->orderBy('id', 'asc')
                ->chunk(500, function ($yarnStockSummaries) use (&$counter) {

                    foreach($yarnStockSummaries as $summary) {
                        $yarnLot = $summary->yarn_lot;
                        $yarnBrand = $summary->yarn_brand;
                        $yarnColor = $summary->yarn_color;
                        $yarnCount = DB::table('yarn_counts')
                                        ->where('id', $summary->yarn_count_id)
                                        ->first();

                        $yarnType = DB::table('composition_types')
                                        ->where('id', $summary->yarn_type_id)
                                        ->first();

                        $yarnComposition = DB::table('yarn_compositions')
                                        ->where('id', $summary->yarn_composition_id)
                                        ->first();

                        $meta = [
                            "yarn_lot" => $yarnLot,
                            "yarn_type" => $yarnType ? $yarnType->name : null,
                            "yarn_brand" => $yarnBrand,
                            "yarn_color" => $yarnColor,
                            "yarn_count" => $yarnCount ? $yarnCount->yarn_count : null,
                            "yarn_composition" => $yarnComposition ? $yarnComposition->yarn_composition : null,
                        ];

                        DB::table('yarn_stock_summaries')
                            ->where('id', $summary->id)
                            ->update(['meta' => json_encode($meta)]);

                        $this->info('Counter = '.++$counter);
                    }
                });

            DB::commit();

            $this->info('Done!!!');
            return 1;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->info($e->getMessage());
            $this->info($e->getLine());
            return 0;
        }
    }
}
