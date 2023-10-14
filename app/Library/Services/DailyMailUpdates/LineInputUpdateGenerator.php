<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inputdroplets\Services\DailyLineInputMailReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class LineInputUpdateGenerator implements DailyMailUpdateContract
{
    private $dateWiseInput;

    public function getFolderName(): string
    {
        return 'line_input_update';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyLineInputUpdateAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_line_input_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'reportData' => $this->dateWiseInput,
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

        $this->dateWiseInput = (new DailyLineInputMailReportService())->getReport($date);
        return $this;
    }
}
