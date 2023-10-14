<?php

namespace App\MailChannels\Mailers\Inventory;

use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class DailyFinishFabricReceiveUpdateMail implements MailingChannelContract, MailingChannelAttachAbleContract
{

    public function attachment(): array
    {
        $filePath = MailDTO::getDailyFinishFabricReceiveUpdateAttachmentName();

        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        return [
            'title' => 'Daily Finish Fabric Receive',
            'signature' => MailSignature::query()->first(),
        ];
    }

    public function view(): string
    {
        return 'auto_mail.inventory.daily_finish_fabric_receive_update.mail_body';
    }

    public function subject(): string
    {
        return 'Daily Finish Fabric Receive Update';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('daily_finish_fabric_receive');
    }
}
