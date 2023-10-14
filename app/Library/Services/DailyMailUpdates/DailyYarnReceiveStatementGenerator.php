<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Services\YarnReceive\YarnReceiveReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class DailyYarnReceiveStatementGenerator implements DailyMailUpdateContract
{
    private $reportData;
    private $store;

    public function setStoreId($store): self
    {
        $this->store = $store;
        return $this;
    }

    private function getStore()
    {
        return $this->store;
    }

    /**
     * @return mixed
     */
    public function getReportData()
    {
        return $this->reportData;
    }

    public function getFolderName(): string
    {
        return 'yarn_receive_statement';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailyYarnReceiveStatementAttachmentName($this->getStore()->name);
    }

    public function getViewPath(): string
    {
        return 'auto_mail.inventory.daily_yarn_received_statement.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'storeName' => $this->getStore()->name,
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

        $data = (new Request())->replace([
            'date' => $date,
            'factory_id' => $this->getFactory()->id,
            'store_id' => $this->getStore()->id
        ]);

        $this->reportData = YarnReceiveReportService::setData($data)->dailyWiseFormat();
        return $this;
    }
}
