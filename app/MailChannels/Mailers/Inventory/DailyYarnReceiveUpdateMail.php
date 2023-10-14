<?php

namespace App\MailChannels\Mailers\Inventory;

use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class DailyYarnReceiveUpdateMail implements MailingChannelContract, MailingChannelAttachAbleContract
{

    public function attachment(): array
    {
        $filePath = MailDTO::getDailyYarnReceiveUpdateAttachmentName();
        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        return [
            'title' => 'Daily Yarn Receive',
            'signature' => MailSignature::query()->first(),
        ];
    }

    public function view(): string
    {
        return 'auto_mail.inventory.daily_yarn_received_statement.mail_body';
    }

    public function subject(): string
    {
        return 'Daily Yarn Receive Update';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('daily_yarn_received_statement');
    }
}
