<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateKnitting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:knitting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will truncate all data from knitting';

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
            $this->info('Removing all data from knitting...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::beginTransaction();

            DB::table('fabric_sales_orders')->truncate();
            DB::table('fabric_sales_order_details')->truncate();
            DB::table('knit_cards')->truncate();
            DB::table('knit_card_machine_details')->truncate();
            DB::table('knit_card_yarn_details')->truncate();
            DB::table('knit_program_rolls')->truncate();
            DB::table('knitting_programs')->truncate();
            DB::table('knitting_program_collar_cuffs')->truncate();
            DB::table('knitting_program_collar_cuff_productions')->truncate();
            DB::table('knitting_program_color_qtys')->truncate();
            DB::table('knitting_program_machine_distributions')->truncate();
            DB::table('knitting_program_stripe_details')->truncate();
            DB::table('knitting_roll_delivery_challans')->truncate();
            DB::table('knitting_roll_delivery_challan_details')->truncate();
            DB::table('planning_infos')->truncate();
            DB::table('planning_info_details')->truncate();
            DB::table('roll_wise_fabric_deliveries')->truncate();
            DB::table('yarn_allocations')->truncate();
            DB::table('yarn_allocation_booking_details')->truncate();
            DB::table('yarn_allocation_details')->truncate();
            DB::table('yarn_requisitions')->truncate();
            DB::table('yarn_requisition_details')->truncate();

            DB::commit();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->info('Done!!!');
            return 1;
        } catch (\Throwable $e) {
            $this->info($e->getMessage());
            return 0;
        }
    }
}
