<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class FinishingUpdateGenerator extends ProductionUpdates implements DailyMailUpdateContract
{
    /**
     * @return string
     */
    public function getFolderName(): string
    {
        return 'finishing_update';
    }

    /**
     * @return string
     */
    public function getStoragePath(): string
    {
        return MailDTO::getDailyFinishingUpdateAttachmentName();
    }

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return 'auto_mail.daily_finishing_update.mail_attachment';
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }

    /**
     * @return array
     */
    public function getViewData(): array
    {
        return [
            'finishingData' => $this->totalData,
            'factoryInfo' => $this->getFactory()
        ];
    }
}
