<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsBookingUOMId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autofill:trims_booking_uom_id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It autofill cons_uom_id with cons_uom_value';

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

        TrimsBookingDetails::query()
            ->whereNull('cons_uom_id')
            ->get()
            ->each(function ($bookingDetail) {

                if ( !$bookingDetail->cons_uom_value ) return;

                switch (strtolower($bookingDetail->cons_uom_value)) {
                    case 'cone':
                    case 'con':
                        $bookingDetail->update(['cons_uom_id' => 49]);
                        break;

                    case 'pcs':
                        $bookingDetail->update(['cons_uom_id' => 51]);
                        break;
                    default:
                        $uom = UnitOfMeasurement::query()
                            ->where('unit_of_measurement', $bookingDetail->cons_uom_value)
                            ->first();

                        if ($uom) {
                            $bookingDetail->update(['cons_uom_id' => $uom->id]);
                        }
                }
            });

        $this->info('Done...');


        return 0;
    }
}
