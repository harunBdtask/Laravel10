<?php

namespace App\Console\Commands\Mail\Daily\Protracker;

use App\Facades\MailChannelFacade;
use App\Library\Services\DailyMailPdfDecorator;
use App\Library\Services\DailyMailUpdates\HourlyFinishingProductionUpdateGenerator;
use App\MailChannels\Mailers\HourlyFinishingProductionUpdateMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class HourlyFinishingProductionUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:hourly-finishing-production-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will sent an email with hourly finishing production update attachment';

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
        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }

        $request = (new Request())->replace([
            'date' => $date,
        ]);

        $outputUpdate = new HourlyFinishingProductionUpdateGenerator();
        DailyMailPdfDecorator::make($outputUpdate->generate($request))->pdf();

        MailChannelFacade::for(new HourlyFinishingProductionUpdateMail());
        return 0;
    }
}
