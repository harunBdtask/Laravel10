<?php

namespace App\MailChannels\Mailers\Inventory;

use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class DailyYarnIssueUpdateMail implements MailingChannelContract, MailingChannelAttachAbleContract
{

    public function attachment(): array
    {
        $filePath = MailDTO::getDailyYarnIssueUpdateAttachmentName();
        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        return [
            'title' => 'Daily Yarn Issue',
            'signature' => MailSignature::query()->first(),
        ];
    }

    public function view(): string
    {
        return 'auto_mail.inventory.daily_yarn_issue_update.mail_body';
    }

    public function subject(): string
    {
        return 'Daily Yarn Issue Note';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('daily_yarn_issue_report');
    }
}
