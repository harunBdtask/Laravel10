<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\CuttingUpdateV2Generator;
use App\Library\Services\DailyMailUpdates\DailyPrintEmbrGenerator;
use App\MailChannels\Mailers\DailyCuttingUpdateV2Mail;
use App\MailChannels\Mailers\DailyPrintEmbrMail;
use Illuminate\Console\Command;

class PrintEmbrUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-print_embr_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily embr. update attachment';

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
        $printEmbr = new DailyPrintEmbrGenerator();
        DailyMailPdfDecorator::make($printEmbr->generate())->pdf();

        MailChannelFacade::for(new DailyPrintEmbrMail());
        return 0;
    }
}
