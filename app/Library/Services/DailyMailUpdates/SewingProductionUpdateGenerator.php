<?php

namespace App\Library\Services\DailyMailUpdates;

use App\MailChannels\MailDTO;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Sewingdroplets\Services\LineWiseOutputService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class SewingProductionUpdateGenerator implements DailyMailUpdateContract
{
    private $sewingOutputs;

    public function getFolderName(): string
    {
        return 'sewing_update';
    }

    public function getStoragePath(): string
    {
        return MailDTO::getDailySewingProductionUpdateAttachmentName();
    }

    public function getViewPath(): string
    {
        return 'auto_mail.daily_sewing_update.mail_attachment';
    }

    public function getViewData(): array
    {
        return [
            'sewing_outputs' => $this->sewingOutputs['sewing_outputs'],
            'sewing_outputs_floor_wise' => $this->sewingOutputs['sewing_outputs']->groupBy('floor_no'),
            'summary' => $this->sewingOutputs['summary'],
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

        $this->sewingOutputs['sewing_outputs'] = FinishingProductionReport::with([
                'floor:id,floor_no',
                'line:id,line_no',
                'buyer:id,name',
                'order:id,style_name,job_no',
                'color:id,name',
                'purchaseOrder:id,po_no',
            ])->select(
                'buyer_id',
                'order_id',
                'purchase_order_id',
                'color_id',
                'production_date',
                'sewing_input',
                'sewing_output',
                'floor_id',
                'line_id'
            )->where(function ($query) {
                return $query->where('sewing_output', '>', 0)
                    ->orWhere('sewing_input', '>', 0);
            })
            ->whereDate('production_date', $date)
            ->orderBy('line_id')
            ->get()
            ->map(function ($item) {
                $totalRejection = FinishingProductionReport::orderColorLineWiseTotalRejectionQty($item->order_id, $item->color_id, $item->line_id);
                $totalOutput = FinishingProductionReport::orderColorLineWiseTotalOutputQty($item->order_id, $item->color_id, $item->line_id);
                $totalInput = FinishingProductionReport::orderColorLineWiseTotalInputQty($item->order_id, $item->color_id, $item->line_id);
                return [
                    'buyer_name' => $item->buyer->name ?? '',
                    'reference_no' => $item->order->job_no ?? '',
                    'style_name' => $item->order->style_name ?? '',
                    'po_no' => $item->purchaseOrder->po_no ?? '',
                    'color' => $item->color->name ?? '',
                    'total_qty' => $item->order->pq_qty_sum ?? '',
                    'today_output' => $item->sewing_output,
                    'total_output' => FinishingProductionReport::orderColorLineWiseTotalOutputQty($item->order_id, $item->color_id, $item->line_id),
                    'wip' => $totalInput - $totalOutput - $totalRejection,
                    'line_id' => $item->line_id,
                    'floor_id' => $item->floor_id,
                    'line_no' => $item->line->line_no ?? '',
                    'floor_no' => $item->floor->floor_no ?? '',
                ];
            });

        $this->sewingOutputs['summary'] = SewingLineTarget::query()
            ->where('target_date', $date)
            ->whereIn('floor_id', $this->sewingOutputs['sewing_outputs']->pluck('floor_id')->unique()->values()->toArray())
            ->get();

        return $this;
    }
}
