<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Sewingdroplets\Services\LineWiseOutputService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class DailyOutputUpdateGenerator implements DailyMailUpdateContract
{
    private $outputService, $reportData, $floors;

    public function __construct()
    {
        $this->outputService = new LineWiseOutputService();
    }

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'daily_output_update';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyOutputAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_output_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'sewing_outputs' => $this->getReportData()['sewing_outputs'],
            'floor_total' => $this->getReportData()['floor_total'],
            'date' => date('Y-m-d'),
            'floors' => $this->floors,
            'factoryInfo' => $this->getFactory()
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }

    public function generate(): DailyOutputUpdateGenerator
    {
        $this->floors = Floor::with('lines')->get()->sortBy('sort');

        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }

        $this->reportData = $this->outputService->sewingOutputsByDate($this->floors, $date);

        return $this;
    }
}
