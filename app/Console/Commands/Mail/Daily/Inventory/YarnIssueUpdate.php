<?php

namespace App\Console\Commands\Mail\Daily\Inventory;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\YarnIssueUpdateGenerator;
use App\MailChannels\Mailers\Inventory\DailyYarnIssueUpdateMail;
use Illuminate\Console\Command;

class YarnIssueUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-yarn-issue-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily yarn issue update attachment';

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
        $yarnIssueUpdate = new YarnIssueUpdateGenerator;
        DailyMailPdfDecorator::make($yarnIssueUpdate->generate())->pdf();

        MailChannelFacade::for(new DailyYarnIssueUpdateMail);
        return 0;
    }
}
