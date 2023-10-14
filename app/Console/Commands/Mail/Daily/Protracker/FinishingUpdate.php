<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\FinishingUpdateGenerator;
use App\MailChannels\Mailers\DailyFinishUpdateMail;
use Illuminate\Console\Command;

class FinishingUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-finishing-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily Finishing update attachment';

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
        $finishingUpdate = new FinishingUpdateGenerator();
        $finishingUpdate->setColumnName('poly_qty');
        $finishingUpdate->setTotalColumnName('total_poly');
        DailyMailPdfDecorator::make($finishingUpdate->generate())->pdf();

        MailChannelFacade::for(new DailyFinishUpdateMail());
        return 0;
    }
}
