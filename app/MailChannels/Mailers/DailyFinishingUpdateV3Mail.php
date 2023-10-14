<?php

namespace App\MailChannels\Mailers;

use App\Library\Services\DailyMailUpdates\DailyProductionSummary;
use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class DailyFinishingUpdateV3Mail implements MailingChannelContract, MailingChannelAttachAbleContract
{
    public function attachment(): array
    {
        $filePath = MailDTO::getDailyFinishingUpdateV3AttachmentName();
        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        $productionSummary = DailyProductionSummary::setDate(date('Y-m-d'))->generateSummary();
        return array_merge(
            [
                'title' => 'Daily Finishing Update V3',
                'signature' => MailSignature::query()->first(),
            ],
            $productionSummary
        );
    }

    public function view(): string
    {
        return 'auto_mail.daily_finishing_update_v3.mail_body';
    }

    public function subject(): string
    {
        return 'Daily Finishing Update V3';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('daily_finishing_production_report_v3');
    }
}
