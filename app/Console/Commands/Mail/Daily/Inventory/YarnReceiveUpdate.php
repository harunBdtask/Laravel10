<?php

namespace App\Console\Commands\Mail\Daily\Inventory;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\DailyYarnReceiveStatementGenerator;
use App\Library\Services\DailyMailUpdates\YarnReceiveUpdateGenerator;
use App\MailChannels\Mailers\Inventory\DailyYarnReceiveUpdateMail;
use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\Inventory\Models\Store;

class YarnReceiveUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:daily-yarn-received-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with daily yarn receive statement attachment';

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
        $receiveUpdate = new YarnReceiveUpdateGenerator;
        DailyMailPdfDecorator::make($receiveUpdate->generate())->pdf();

        MailChannelFacade::for(new DailyYarnReceiveUpdateMail());
        return 0;
    }
}
