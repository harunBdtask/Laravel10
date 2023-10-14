<?php

namespace App\MailChannels\Mailers;

use App\Library\Services\DailyMailUpdates\DailyProductionSummary;
use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class DailyCuttingUpdateV2Mail implements MailingChannelContract, MailingChannelAttachAbleContract
{

    public function attachment(): array
    {
        $filePath = MailDTO::getDailyCuttingUpdateV2AttachmentName();
        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        $productionSummary = DailyProductionSummary::setDate(date('Y-m-d'))->generateSummary();
        return array_merge(
            [
                'title' => 'Daily Cutting Update V2',
                'signature' => MailSignature::query()->first(),
            ],
            $productionSummary
        );
    }

    public function view(): string
    {
        return 'auto_mail.daily_cutting_update_v2.mail_body';
    }

    public function subject(): string
    {
        return 'Daily Cutting Update V2';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('daily_cutting_report_v2');
    }
}
