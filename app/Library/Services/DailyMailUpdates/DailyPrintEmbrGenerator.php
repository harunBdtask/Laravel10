<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\DailyPrintEmbrReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class DailyPrintEmbrGenerator implements DailyMailUpdateContract
{
    private $reportData;

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'print_embr';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyPrintEmbrAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_print_embr.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'reportData' => $this->getReportData(),
            'factoryInfo' => $this->getFactory()
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }

    public function generate(): DailyPrintEmbrGenerator
    {
        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }
        $this->reportData = (new DailyPrintEmbrReportService())->generateReport($date);
        return $this;
    }
}
