<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;

class MachineProfileUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'machine-profile:last-maintenance';

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
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();

        $mcMachine = McMachine::query()->get();
        $tenor = 90;

        $mcMachine->each(function ($machine) use($tenor){
            if ($machine->last_maintenance){
                $maintenanceDate = Carbon::make($machine->last_maintenance)->addDays($tenor);
                $machine->update([
                    'tenor' => 90,
                    'next_maintenance' => $maintenanceDate
                ]);
            }
        });

        DB::commit();

        $this->info("Machine Profile Data changed successfully");
    }
}
