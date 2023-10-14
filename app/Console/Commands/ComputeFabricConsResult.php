<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;

class ComputeFabricConsResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compute:fabric-cons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute fabric cons result for old bundle card details';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bundleCardDetails = BundleCardGenerationDetail::query()
            ->where('is_regenerated', 0)
            ->where('is_manual', 0)
            ->get();

        foreach ($bundleCardDetails as $bundleCardDetail) {
            $bookingCons = $bundleCardDetail->booking_consumption;
            $totalWeight = collect($bundleCardDetail->rolls)->sum('weight');
            $totalQty = collect($bundleCardDetail->po_details)->sum('quantity');
            $actualCons = $totalWeight > 0 ? (($totalWeight / $totalQty) * 12) : 0;
            $result = $actualCons - $bookingCons > 0 ? 0 : 1;
            $bundleCardDetail->update(['cons_result' => $result]);
        }

        return 0;
    }
}
