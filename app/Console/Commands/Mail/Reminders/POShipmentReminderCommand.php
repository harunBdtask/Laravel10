<?php

namespace App\Console\Commands\Mail\Reminders;

use App\Facades\MailChannelFacade;
use App\MailChannels\Mailers\POShipmentReminder;
use Illuminate\Console\Command;

class POShipmentReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:po-shipment-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind 7 Days Later Shipment Po';

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
        MailChannelFacade::for(new POShipmentReminder());
        return 0;
    }
}
