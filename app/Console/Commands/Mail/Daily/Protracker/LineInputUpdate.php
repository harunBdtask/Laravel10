<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\LineInputUpdateGenerator;
use App\MailChannels\Mailers\DailyLineInputUpdateMail;
use Illuminate\Console\Command;

class LineInputUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-line-input-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily line input update attachment';

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
        $inputUpdate = new LineInputUpdateGenerator;
        DailyMailPdfDecorator::make($inputUpdate->generate())->pdf();

        MailChannelFacade::for(new DailyLineInputUpdateMail);
        return 0;
    }
}
