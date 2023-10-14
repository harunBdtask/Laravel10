<?php

namespace App\MailChannels\Mailers;

use App\Library\Services\DailyMailUpdates\DailyProductionSummary;
use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class DailyFinishUpdateMail implements MailingChannelContract, MailingChannelAttachAbleContract
{

    public function attachment(): array
    {
        $filePath = MailDTO::getDailyFinishingUpdateAttachmentName();
        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        $productionSummary = DailyProductionSummary::setDate(date('Y-m-d'))->generateSummary();
        return array_merge(
            [
                'title' => 'Daily Finish Update',
                'signature' => MailSignature::query()->first(),
            ],
            $productionSummary);
    }

    public function view(): string
    {
        return 'auto_mail.daily_finishing_update.mail_body';
    }

    public function subject(): string
    {
        return 'Daily Finish Update';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('daily_finishing_report');
    }
}
