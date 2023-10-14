<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\SewingProductionUpdateGenerator;
use App\MailChannels\Mailers\DailySewingUpdateMail;
use Illuminate\Console\Command;

class SewingProductionUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-sewing-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily Sewing update attachment';

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
        $sewingUpdate = new SewingProductionUpdateGenerator();
        DailyMailPdfDecorator::make($sewingUpdate->generate())->pdf();

        MailChannelFacade::for(new DailySewingUpdateMail());
        return 0;
    }
}
