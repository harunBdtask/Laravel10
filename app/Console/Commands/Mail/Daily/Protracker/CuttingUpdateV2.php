<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\CuttingUpdateV2Generator;
use App\MailChannels\Mailers\DailyCuttingUpdateV2Mail;
use Illuminate\Console\Command;

class CuttingUpdateV2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-cutting-update-v2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily cutting update v2 attachment';

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
        $cuttingUpdate = new CuttingUpdateV2Generator();
        DailyMailPdfDecorator::make($cuttingUpdate->generate())->pdf();

        MailChannelFacade::for(new DailyCuttingUpdateV2Mail());
        return 0;
    }
}
