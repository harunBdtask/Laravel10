<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\DailyOutputUpdateGenerator;
use App\MailChannels\Mailers\DailyOutputUpdateMail;
use Illuminate\Console\Command;

class OutputUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-output-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily output update attachment';

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
        $outputUpdate = new DailyOutputUpdateGenerator();
        DailyMailPdfDecorator::make($outputUpdate->generate())->pdf();

        MailChannelFacade::for(new DailyOutputUpdateMail());
        return 0;
    }
}
