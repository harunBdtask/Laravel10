<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteArchivedPrintInventoryDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:archived-print-inventory-data-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Archived PrintInventory Data Command';

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
            DB::table('archived_print_inventories')
                ->select('id')
                ->orderBy('id', 'desc')
                ->chunk($chunk_data_count, function ($data) use (&$counter) {
                    DB::beginTransaction();
                    $query = DB::table('print_inventories')->whereIn('id', $data->pluck('id')->toArray());
                    $data_count = $query->count();
                    $query->delete();
                    DB::commit();
                    $counter += $data_count;
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
