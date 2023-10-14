<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use Throwable;

class AccountTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:transaction';

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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Account transaction removed';

    /**
     * @throws Throwable
     */
    public function handle()
    {
        DB::beginTransaction();
        $controlAccounts = AccountInfo::query()
            ->with('controlAccount')
            ->whereNotNull('control_account_id')
            ->groupBy('control_account_id')
            ->get()
            ->pluck('controlAccount');

        $controlAccounts->each(function ($collection) {
            $collection->update(['is_transactional' => 0]);
        });

        $groupAccounts = AccountInfo::query()
            ->with('groupAccount')
            ->whereNotNull('group_account_id')
            ->groupBy('group_account_id')
            ->get()
            ->pluck('groupAccount');

        $groupAccounts->each(function ($collection) {
            $collection->update(['is_transactional' => 0]);
        });

        $parentAccounts = AccountInfo::query()
            ->with('parentAccount')
            ->whereNotNull('parent_account_id')
            ->groupBy('parent_account_id')
            ->get()
            ->pluck('parentAccount');

        $parentAccounts->each(function ($collection) {
            $collection->update(['is_transactional' => 0]);
        });
        DB::commit();

        $this->info("Account transaction changed successfully");
    }
}
