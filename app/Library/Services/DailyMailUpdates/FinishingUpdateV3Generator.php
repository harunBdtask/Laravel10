<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use SkylarkSoft\GoRMG\Finishingdroplets\ValueObjects\FinishingProductionValueObject;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class FinishingUpdateV3Generator implements DailyMailUpdateContract
{
    private $reportData;

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'finishing_update_v3';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyFinishingUpdateV3AttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_finishing_update_v3.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'productions' => $this->getReportData(),
            'factoryInfo' => $this->getFactory(),
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }

    public function generate(): self
    {
        $this->reportData = (new FinishingProductionValueObject())
            ->setFrom(date('Y-m-d'))
            ->setTo(date('Y-m-d'))
            ->report();
        return $this;
    }
}
