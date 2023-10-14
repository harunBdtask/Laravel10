<?php

namespace App\Console\Commands\Mail\Daily\Merchandising;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\OrderPOUpdateGenerator;
use App\MailChannels\Mailers\DailyOrderPOUpdateMail;
use Illuminate\Console\Command;

class OrderPOUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-order-po-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily order received update attachment';

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
        $dailyOrderPOUpdate = new OrderPOUpdateGenerator();
        DailyMailPdfDecorator::make($dailyOrderPOUpdate->generate())->pdf();

        MailChannelFacade::for(new DailyOrderPOUpdateMail());
        return 0;
    }
}
