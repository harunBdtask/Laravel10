<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\DailyYarnIssueStatementService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceive\DailyYarnReceiveMailReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class YarnReceiveUpdateGenerator implements DailyMailUpdateContract
{
    private $reportData;

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'yarn_receive_update';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyYarnReceiveUpdateAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.inventory.daily_yarn_received_statement.mail_attachment';
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

    public function generate(): self
    {
        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }
        $this->reportData = (new DailyYarnReceiveMailReportService())->generate($date);
        return $this;
    }
}
