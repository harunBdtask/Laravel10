<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssue;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryCalculator;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueStockService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryCalculator;

class YarnStockFixClosingBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closing-balance-fix:yarn-store';

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
            $this->info('Fixing...');
            DB::beginTransaction();

            $importData = json_decode(File::get(public_path("/files/yarn_stock.json")), true);
            foreach (collect($importData)->chunk(50) as $chunkData) {
                $yarnIssue = $this->createIssue();
                foreach ($chunkData as $value) {
                    $yarnIssueDetail = $this->createIssueDetails($yarnIssue, $value);
                    (new YarnIssueStockService())->created($yarnIssueDetail);
                }
            };

            DB::commit();
            $this->info('Done! :D');
            return 1;
        } catch (\Throwable $e) {
            $this->info($e->getMessage());
            return 0;
        }
    }

    public function createIssue()
    {
        $formData = [
            'factory_id' => 1,
            'issue_basis' => 1,
            'issue_purpose' => 12,
            'issue_date' => date('Y-m-d'),
            'supplier_id' => 59,
            'knitting_source' => 1,
            'issue_to' => 1,
            'challan_no' => rand(10000, 99999),
        ];

        return YarnIssue::query()->create($formData);
    }

    public function createIssueDetails($yarnIssue, $details)
    {
        $issueQty = $details['qty'] > $details['act_qty'] ? $details['qty'] - $details['act_qty'] : $details['qty'];
        $formData = [
            'store_id' => 5,
            'yarn_lot' => $details['yarn_lot'],
            'yarn_count_id' => $details['yarn_count_id'],
            'yarn_composition_id' => $details['yarn_composition_id'],
            'yarn_type_id' => $details['yarn_type_id'],
            'yarn_color' => $details['yarn_color'] ?? null,
            'yarn_brand' => $details['yarn_brand'],
            'uom_id' => $details['uom_id'],
            'rate' => $details['rate'],
            'yarn_issue_id' => $yarnIssue->id,
            'issue_qty' => $issueQty
        ];

        return YarnIssueDetail::query()->create($formData);
    }
}
