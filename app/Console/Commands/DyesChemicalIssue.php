<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsIssue;
use Throwable;

class DyesChemicalIssue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:system-generate-id';

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
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->info('Start Execution');
            $dyesChemicalIssue = DyesChemicalsIssue::query()->get();

            DyesChemicalsIssue::query()->get()->each(function ($issue) {
                $generate = str_pad($issue->id ?? 0, 5, "0", STR_PAD_LEFT);
                $prefix = getPrefix() . 'DCI-' . date('y') . '-' . $generate;
                $issue->update([
                    'system_generate_id' => $prefix,
                ]);
            });
            $this->info('End Execution');
            DB::commit();
            $this->info('Success');
        } catch (Exception $e) {
            DB::rollBack();
            $this->info($e);
        }
    }
}
