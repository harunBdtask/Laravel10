<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use Throwable;

class SubDyeingBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:batch-detail';

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
     *
     * @throws Throwable
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->info('Start Execution');

            SubDyeingBatchDetail::query()
                ->doesntHave('subDyeingBatch')
                ->delete();

            $this->info('End Execution');
            DB::commit();
            $this->info('Success');
        } catch (Exception $e) {
            DB::rollBack();
            $this->info($e->getMessage());
        }
    }
}
