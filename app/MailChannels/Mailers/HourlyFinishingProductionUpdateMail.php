<?php

namespace App\MailChannels\Mailers;

use App\Library\Services\DailyMailUpdates\DailyProductionSummary;
use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class HourlyFinishingProductionUpdateMail implements MailingChannelContract, MailingChannelAttachAbleContract
{

    public function attachment(): array
    {
        $filePath = MailDTO::getHourlyFinishingProductionAttachmentName();
        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        $productionSummary = DailyProductionSummary::setDate(date('Y-m-d'))->generateSummary();

        return array_merge(
            [
                'title' => 'Hourly Finishing Production Update',
                'signature' => MailSignature::query()->first(),
            ],
            $productionSummary);
    }

    public function view(): string
    {
        return 'auto_mail.hourly_finishing_production_update.mail_body';
    }

    public function subject(): string
    {
        return 'Hourly Finishing Production Update';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('hourly_finishing_production_report');
    }
}
