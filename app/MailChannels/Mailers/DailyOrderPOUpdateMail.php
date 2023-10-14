<?php

namespace App\MailChannels\Mailers;

use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class DailyOrderPOUpdateMail implements MailingChannelContract, MailingChannelAttachAbleContract
{
    public function attachment(): array
    {
        $filePath = MailDTO::getDailyOrderPOUpdateAttachmentName();
        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        return [
            'title' => 'Daily Order Received Update',
            'signature' => MailSignature::query()->first(),
        ];
    }

    public function view(): string
    {
        return 'auto_mail.daily_order_po_update.mail_body';
    }

    public function subject(): string
    {
        return 'Daily Order Received Update';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('daily_order_po_report');
    }
}
