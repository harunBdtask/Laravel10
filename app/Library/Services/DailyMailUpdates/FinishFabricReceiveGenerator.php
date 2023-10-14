<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService\DailyFinishFabricReceiveMailReportService;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService\FinishFabricReceiveReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class FinishFabricReceiveGenerator implements DailyMailUpdateContract
{
    private $reportData;

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'finish_fabric_receive';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyFinishFabricReceiveUpdateAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.inventory.daily_finish_fabric_receive_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'fabricReceives' => $this->getReportData(),
            'factoryInfo' => $this->getFactory()
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }

    public function generate(): self
    {
        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }

        $this->reportData = (new DailyFinishFabricReceiveMailReportService())->getReportData($date);
        return $this;
    }
}
