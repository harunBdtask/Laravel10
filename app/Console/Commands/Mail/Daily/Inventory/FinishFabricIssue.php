<?php

namespace App\Console\Commands\Mail\Daily\Inventory;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\FinishFabricIssueGenerator;
use App\MailChannels\Mailers\Inventory\DailyFinishFabricIssueUpdateMail;
use Illuminate\Console\Command;

class FinishFabricIssue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-finish-fabric-issue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily finish fabric issue update attachment';

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
        $finishFabricIssue = new FinishFabricIssueGenerator();
        DailyMailPdfDecorator::make($finishFabricIssue->generate())->pdf();

        MailChannelFacade::for(new DailyFinishFabricIssueUpdateMail());
        return 0;
    }
}
