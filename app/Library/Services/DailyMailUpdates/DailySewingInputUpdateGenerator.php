<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class DailySewingInputUpdateGenerator implements DailyMailUpdateContract
{
    private $reportData;

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'daily_sewing_input_update';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailySewingInputAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_sewing_input_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'date_wise_input' => $this->getReportData(),
            'date' => date('Y-m-d'),
            'factoryInfo' => $this->getFactory()
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }

    public function generate(): DailySewingInputUpdateGenerator
    {
        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }

        $this->reportData = FinishingProductionReport::query()
            ->whereDate('production_date', $date)
            ->orderBy('floor_id')
            ->where('sewing_input', '>', 0)
            ->get();

        return $this;
    }
}
