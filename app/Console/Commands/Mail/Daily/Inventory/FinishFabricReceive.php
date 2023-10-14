<?php

namespace App\Console\Commands\Mail\Daily\Inventory;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\FinishFabricReceiveGenerator;
use App\MailChannels\Mailers\Inventory\DailyFinishFabricReceiveUpdateMail;
use Illuminate\Console\Command;

class FinishFabricReceive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-finish-fabric-receive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily finish fabric receive update attachment';

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
        $finishFabricReceive = new FinishFabricReceiveGenerator();
        DailyMailPdfDecorator::make($finishFabricReceive->generate())->pdf();

        MailChannelFacade::for(new DailyFinishFabricReceiveUpdateMail());
        return 0;
    }
}
