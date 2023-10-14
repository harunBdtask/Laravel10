<?php

namespace App\MailChannels\Mailers;

use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class POShipmentReminder implements MailingChannelContract
{
    public function provideData(): array
    {
        $sevenDaysLater = Carbon::now()->addDays(7)->format('Y-m-d');
        $sevenDaysLaterShipment = PurchaseOrder::query()
            ->with(['order:id,style_name,job_no', 'buyer:id,name', 'poDetails.garmentItem'])
            ->withSum('poDetails as poQty', 'quantity')
            ->where('ex_factory_date', $sevenDaysLater)
            ->get();

        return [
            'title' => 'TODAY DELIVERY ( EXPORT ORDERS )',
            'po_details' => $sevenDaysLaterShipment
        ];
    }

    public function view(): string
    {
        return 'auto_mail.po_shipment_reminder.mail_body';
    }

    public function subject(): string
    {
        return 'TODAY DELIVERY ( EXPORT ORDERS )';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('po_shipment_reminder');
    }
}
