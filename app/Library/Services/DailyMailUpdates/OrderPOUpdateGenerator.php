<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Sample\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class OrderPOUpdateGenerator implements DailyMailUpdateContract
{
    private $orders;

    public function generate(): self
    {
        $this->orders = $this->formatOrder();
        return $this;
    }

    private function getTodayOrder()
    {
        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }

        return Order::query()
            ->with(['purchaseOrders.purchaseOrderDetails.color', 'purchaseOrders.createdBy:id,screen_name'])
            ->withCount('purchaseOrders as number_of_po')
            ->whereDate('created_at', $date)
            ->get();
    }

    /**
     * @return Collection
     */
    private function formatOrder(): Collection
    {
        return collect($this->getTodayOrder())->map(function ($order) {

            $purchaseOrders = [];

            if (collect($order->purchaseOrders)->count()) {
                foreach ($order->purchaseOrders as $purchaseOrder) {
                    $purchaseOrders[] = [
                        'po_no' => $purchaseOrder->po_no,
                        'shipment_date' => $purchaseOrder->ex_factory_date,
                        'received_date' => $purchaseOrder->po_receive_date,
                        'account' => $purchaseOrder->createdBy->screen_name,
                    ];
                }
            } else {
                $purchaseOrders[] = [
                    'po_no' => '',
                    'received_date' => '',
                    'shipment_date' => '',
                    'account' => null,
                ];
            }


            return [
                'buyer' => $order->buyer->name,
                'reference_no' => $order->reference_no,
                'style_name' => $order->style_name,
                'total_qty' => $order->pq_qty_sum,
                'number_of_po' => $order->number_of_po ?? 0,
                'purchase_orders' => $purchaseOrders
            ];
        });
    }

    public function getFolderName(): string
    {
        return 'order_po_update';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyOrderPOUpdateAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_order_po_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'orders' => $this->orders,
            'factoryInfo' => $this->getFactory()
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }
}
