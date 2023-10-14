<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssue;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssueDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceive;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use Throwable;

class OrderWiseMaterialReceiveAndIssueDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order-wise-receive-issue:delete';

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

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->info('Start Execution');
            SubGreyStoreReceive::query()->doesntHave('textileOrder')->delete();
            SubGreyStoreReceiveDetails::query()->doesntHave('subGreyStoreReceive')->delete();

            SubGreyStoreIssue::query()->doesntHave('textileOrder')->delete();
            SubGreyStoreIssueDetail::query()->doesntHave('subGreyStoreIssue')->delete();

            $this->info('End Execution');
            DB::commit();

            $this->info('Success');
        } catch (Exception $e) {
            DB::rollBack();

            $this->error($e->getMessage());
        }
    }
}
