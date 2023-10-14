<?php

namespace App\Library\Services\DailyMailUpdates;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\FinishingTarget;
use SkylarkSoft\GoRMG\Iedroplets\Models\CuttingTarget;

class ProductionUpdates
{
    protected $columnName;
    protected $totalColumnName;
    protected $totalData;
    protected $todayData;

    /**
     * @param mixed $columnName
     */
    public function setColumnName($columnName): void
    {
        $this->columnName = $columnName;
    }

    /**
     * @param mixed $totalColumnName
     */
    public function setTotalColumnName($totalColumnName): void
    {
        $this->totalColumnName = $totalColumnName;
    }

    public function generate(): self
    {
        $this->getTodayData();
        $this->getTotalData();
        return $this;
    }

    private function getDate()
    {
        $date = date('Y-m-d');
        if ((int)date('H') >= 0 && (int)date('H') <= 6) {
            $date = Carbon::now()->subDay()->format('Y-m-d');
        }
        return $date;
    }

    protected function getTodayData()
    {
        $rawQuery = [
            'buyer_id',
            'order_id',
            'purchase_order_id',
            'production_date',
            "SUM($this->columnName) AS today_$this->columnName"
        ];

        $this->todayData = DateAndColorWiseProduction::query()
            ->selectRaw(implode(',', $rawQuery))
            ->whereDate('production_date', $this->getDate())
            ->groupBy(['buyer_id', 'order_id', 'purchase_order_id'])
            ->withoutGlobalScope('factoryId')
            ->get();
    }

    protected function getTotalData()
    {
        $rawQuery = ['buyer_id', 'order_id', 'purchase_order_id', 'color_id', "SUM($this->totalColumnName) AS total_$this->totalColumnName"];

        $purchaseOrders = $this->todayData->pluck('purchase_order_id');

        $summary = CuttingTarget::query()
            ->whereDate('target_date', $this->getDate())
            ->get();

        $finishingSummary = FinishingTarget::query()
            ->whereDate('production_date', $this->getDate())
            ->get();

        $this->totalData = TotalProductionReport::query()
            ->with(['buyer:id,name', 'order:id,style_name,job_no', 'purchaseOrder:id,po_no,po_quantity,ex_factory_date', 'color:id,name', 'purchaseOrder.colors:id,name'])
            ->selectRaw(implode(',', $rawQuery))
            ->whereIn('purchase_order_id', $purchaseOrders)
            ->groupBy('buyer_id', 'order_id', 'purchase_order_id')
            ->withoutGlobalScope('factoryId')
            ->get()->map(function ($collection) use ($summary, $finishingSummary) {
                $collection["today_$this->columnName"] = $this->todayData
                    ->where('purchase_order_id', $collection['purchase_order_id'])
                    ->first()["today_$this->columnName"] ?? null;
                $collection['summary'] = $summary;
                $collection['finishingSummary'] = $finishingSummary;
                return $collection;
            });
    }
}
