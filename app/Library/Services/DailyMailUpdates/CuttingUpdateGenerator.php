<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CuttingUpdateGenerator extends ProductionUpdates implements DailyMailUpdateContract
{
    public function getFolderName(): string
    {
        return 'cutting_update';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyCuttingUpdateAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_cutting_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'cuttingData' => $this->totalData,
            'factoryInfo' => $this->getFactory()
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }
}
