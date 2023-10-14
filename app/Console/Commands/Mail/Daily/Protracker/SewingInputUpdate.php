<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\CuttingUpdateV2Generator;
use App\Library\Services\DailyMailUpdates\DailySewingInputUpdateGenerator;
use App\MailChannels\Mailers\DailyCuttingUpdateV2Mail;
use App\MailChannels\Mailers\DailySewingInputUpdateMail;
use App\MailChannels\Mailers\DailySewingUpdateMail;
use Illuminate\Console\Command;

class SewingInputUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-sewing-input-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily sewing input attachment';

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
        $sewingInput = new DailySewingInputUpdateGenerator();
        DailyMailPdfDecorator::make($sewingInput->generate())->pdf();

        MailChannelFacade::for(new DailySewingInputUpdateMail());
        return 0;
    }
}
