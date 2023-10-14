<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\CuttingUpdateGenerator;
use App\MailChannels\Mailers\DailyCuttingUpdateMail;
use Illuminate\Console\Command;

class CuttingUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-cutting-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily cutting update attachment';

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
        $cuttingUpdate = new CuttingUpdateGenerator();
        $cuttingUpdate->setColumnName("cutting_qty");
        $cuttingUpdate->setTotalColumnName("total_cutting");
        DailyMailPdfDecorator::make($cuttingUpdate->generate())->pdf();

        MailChannelFacade::for(new DailyCuttingUpdateMail());
        return 0;
    }
}
