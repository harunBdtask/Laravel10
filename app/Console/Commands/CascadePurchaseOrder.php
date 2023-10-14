<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\Sample\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class CascadePurchaseOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cascade:purchase-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cascade Purchase Order Table';

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
     * @return void
     */
    public function handle(): void
    {
        try {
            $deletedOrders = Order::query()->onlyTrashed()->pluck('id');
            PurchaseOrder::query()->whereIn('order_id', $deletedOrders)->update([
                'deleted_at' => now(),
            ]);
            $this->info("Purchase Order Cascade Successfully");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
