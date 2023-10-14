<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService\DailyFinishFabricIssueMailReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class FinishFabricIssueGenerator implements DailyMailUpdateContract
{
    private $reportData;

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'finish_fabric_issue';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyFinishFabricIssueUpdateAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.inventory.daily_finish_fabric_issue_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'fabricIssues' => $this->getReportData(),
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

        $this->reportData = (new DailyFinishFabricIssueMailReportService())->getReportData($date);
        return $this;
    }
}
