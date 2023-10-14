<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertArchivedSewingoutputDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:archived-sewing-output';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Archived Sewingoutput Data Command';

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
            $date = Carbon::now()->subDays(180)->toDateString();
            DB::table('sewingoutputs')
                ->orderBy('id', 'desc')
                ->whereDate('created_at', '<=', $date)
                ->chunk($chunk_data_count, function ($sewingoutputs) use (&$counter) {
                    DB::beginTransaction();
                    $data = $this->formatData($sewingoutputs);
                    if (count($data)) {
                        DB::table('archived_sewingoutputs')->insert($data);
                    }
                    DB::commit();
                    $counter += count($data);
                    $this->info("Archived Data count: " . $counter);
                });
            $this->info("Execution ended successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }

    private function formatData($sewingoutputs)
    {
        $data = [];
        foreach ($sewingoutputs as $sewingoutput) {
            if (!DB::table('archived_sewingoutputs')->where('bundle_card_id', $sewingoutput->bundle_card_id)->count()) {
                $this->info("Bundle card id: " . $sewingoutput->bundle_card_id);
                $data[] = [
                    'id' => $sewingoutput->id,
                    'bundle_card_id' => $sewingoutput->bundle_card_id,
                    'output_challan_no' => $sewingoutput->output_challan_no,
                    'challan_no' => $sewingoutput->challan_no,
                    'line_id' => $sewingoutput->line_id,
                    'hour' => $sewingoutput->hour,
                    'status' => $sewingoutput->status,
                    'purchase_order_id' => $sewingoutput->purchase_order_id,
                    'color_id' => $sewingoutput->color_id,
                    'user_id' => $sewingoutput->user_id,
                    'details' => $sewingoutput->details,
                    'factory_id' => $sewingoutput->factory_id,
                    'created_at' => $sewingoutput->created_at,
                    'updated_at' => $sewingoutput->updated_at,
                    'deleted_at' => $sewingoutput->deleted_at,
                ];
            }
        }

        return $data;
    }
}
