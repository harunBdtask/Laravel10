<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\HourlyFinishingProductionReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class HourlyFinishingProductionUpdateGenerator implements DailyMailUpdateContract
{
    private $reportData, $total;

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getFolderName(): string
    {
        return 'hourly_finishing_production';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getHourlyFinishingProductionAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.hourly_finishing_production_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'reportData' => $this->getReportData(),
            'total' => $this->getTotal(),
            'factoryInfo' => $this->getFactory()
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }

    public function generate($request): HourlyFinishingProductionUpdateGenerator
    {
        $reportService = new HourlyFinishingProductionReportService($request);

        $this->reportData = $reportService->report();
        $this->total = $reportService->getTotal();

        return $this;
    }
}
