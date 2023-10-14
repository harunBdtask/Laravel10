<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldBundleCardGenerationCacheData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old-bundle-card-generation-cache-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Old BundleCardGenerationCache Data';

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
            $date = now()->subDays(30)->startOfDay()->toDateTimeString();
            $this->info("Execution started successfully!");
            DB::table('bundle_card_generation_caches')
                ->select('id')
                ->where('created_at', '<', $date)
                ->orderBy('id', 'desc')
                ->chunk($chunk_data_count, function ($data_details) use (&$counter) {
                    DB::beginTransaction();
                    $deleteable_ids = $data_details->pluck('id')->toArray();
                    if (count($deleteable_ids)) {
                        DB::table('bundle_card_generation_caches')->whereIn('id', $deleteable_ids)->delete();
                    }

                    DB::commit();
                    $counter += count($deleteable_ids);
                    $this->info("Deleted Data count: " . $counter);
                });
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }
}
