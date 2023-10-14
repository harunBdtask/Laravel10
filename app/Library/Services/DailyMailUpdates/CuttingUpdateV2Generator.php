<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CuttingUpdateV2Generator implements DailyMailUpdateContract
{
    private $reportData;

    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'cutting_update_v2';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyCuttingUpdateV2AttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_cutting_update_v2.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'cutting_report' => $this->getReportData(),
            'factoryInfo' => $this->getFactory()
        ];
    }

    public function getFactory()
    {
        return Factory::query()->first();
    }

    public function generate(): CuttingUpdateV2Generator
    {
        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }

        $this->reportData = DateTableWiseCutProductionReport::query()
            ->whereDate('production_date', $date)
            ->selectRaw('cutting_floor_id, buyer_id, order_id, purchase_order_id, color_id,
                SUM(cutting_qty - cutting_rejection_qty)  as total_cutting_qty')
            ->where('cutting_qty', '>', 0)
            ->groupBy('cutting_floor_id', 'buyer_id', 'order_id', 'purchase_order_id', 'color_id')
            ->get();

        return $this;
    }
}
