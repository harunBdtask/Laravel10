<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\FinishingUpdateV3Generator;
use App\MailChannels\Mailers\DailyFinishingUpdateV3Mail;
use Illuminate\Console\Command;

class FinishingUpdateV3Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-finishing-update-v3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily finishing update v2 attachment';

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
        $finishingUpdate = new FinishingUpdateV3Generator();
        DailyMailPdfDecorator::make($finishingUpdate->generate())->pdf();
        MailChannelFacade::for(new DailyFinishingUpdateV3Mail());
        return 0;
    }
}
