<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TruncateAccountingRelatedTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:accounting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will truncate all data from Accounting';

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
        $value = $this->ask('Do you really want to delete accounting database? (Yes/No): ');
        if ($value === 'Yes') {
            try {
                $tableArray = [
//                    'bf_accounts',
                    'bf_ac_budgets',
                    'bf_ac_budget_approvals',
                    'bf_ac_budget_details',
//                    'bf_banks',
//                    'bf_bank_accounts',
                    'bf_cheque_books',
                    'bf_cheque_book_details',
//                    'bf_companies',
//                    'bf_cost_centers',
//                    'bf_departments',
                    'bf_fund_requisitions',
                    'bf_fund_requisition_account_approvals',
                    'bf_fund_requisition_audit_approvals',
                    'bf_fund_requisition_details',
                    'bf_fund_requisition_purposes',
                    'bf_journal',
//                    'bf_projects',
//                    'bf_units',
                    'bf_vouchers',
                    'bf_voucher_comments',
//                    'receive_banks',
                    'receive_cheques'
                ];
                $this->info('Removing all data from Accounting!');
                \DB::beginTransaction();
                foreach ($tableArray as $table) {
                    \DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    \DB::table($table)->truncate();
                    \DB::statement('SET FOREIGN_KEY_CHECKS=1');
                }
                \DB::commit();

                $this->info('Done!!!');
                return 1;
            } catch (\Throwable $e) {
                \DB::rollBack();
                $this->info($e->getMessage());
                return 0;
            }
        } else {
            return 0;
        }

    }
}
