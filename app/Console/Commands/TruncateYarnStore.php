<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TruncateYarnStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:yarn-store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will truncate all data from yarn store';

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
            $this->info('Removing all data from yarn store!');
            \DB::beginTransaction();
            \DB::table('yarn_issues')->truncate();
            \DB::table('yarn_issue_details')->truncate();
            \DB::table('yarn_issue_returns')->truncate();
            \DB::table('yarn_issue_return_details')->truncate();
            \DB::table('yarn_receives')->truncate();
            \DB::table('yarn_receive_details')->truncate();
            \DB::table('yarn_receive_returns')->truncate();
            \DB::table('yarn_receive_return_details')->truncate();
            \DB::table('yarn_stock_summaries')->truncate();
            \DB::table('yarn_date_wise_stock_summaries')->truncate();
            \DB::commit();
            $this->info('Done!!!');
            return 1;
        } catch (\Throwable $e) {
            \DB::rollBack();
            $this->info($e->getMessage());
            return 0;
        }
    }
}
