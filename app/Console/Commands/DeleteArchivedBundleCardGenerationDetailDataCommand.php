<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteArchivedBundleCardGenerationDetailDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:archived-bundlecard-generation-details-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Archived BundleCardGenerationDetail Data Command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $counter = 0;
            $chunk_data_count = 500;
            $this->info("Execution started successfully!");
            DB::table('archived_bundle_card_generation_details')
                ->select('sid')
                ->orderBy('sid', 'desc')
                ->chunk($chunk_data_count, function ($data_details) use (&$counter) {
                    DB::beginTransaction();

                    $deleteable_sids = $this->getDeletableSids($data_details);
                    if (count($deleteable_sids)) {
                        DB::table('bundle_card_generation_details')->whereIn('sid', $deleteable_sids)->delete();
                    }

                    DB::commit();
                    $counter += count($deleteable_sids);
                    $this->info("Deleted Data count: " . $counter);
                });
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }

    private function getDeletableSids($data_details): array
    {
        $data = [];
        foreach ($data_details as $detail) {
            if (!DB::table('bundle_cards')->where('bundle_card_generation_detail_id', $detail->sid)->count()) {
                $this->info("SID: " . $detail->sid);
                $data[] = $detail->sid;
            }
        }
        return $data;
    }
}
