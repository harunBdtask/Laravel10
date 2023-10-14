<?php

namespace App\MailChannels\Mailers;

use App\Library\Services\DailyMailUpdates\DailyProductionSummary;
use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;

class DailySewingInputUpdateMail implements MailingChannelContract, MailingChannelAttachAbleContract
{

    public function attachment(): array
    {
        $filePath = MailDTO::getDailySewingInputAttachmentName();
        return [
            Storage::path($filePath)
        ];
    }

    public function provideData(): array
    {
        $productionSummary = DailyProductionSummary::setDate(date('Y-m-d'))->generateSummary();

        return array_merge(
            [
                'title' => 'Daily Sewing Input Update',
                'signature' => MailSignature::query()->first(),
            ],
            $productionSummary);
    }

    public function view(): string
    {
        return 'auto_mail.daily_sewing_input_update.mail_body';
    }

    public function subject(): string
    {
        return 'Daily Sewing Input Update';
    }

    public function receivers(): array
    {
        return MailDTO::getReceivers('daily_sewing_input_update');
    }
}
