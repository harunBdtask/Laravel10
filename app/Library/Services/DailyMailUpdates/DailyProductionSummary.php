<?php

namespace App\Library\Services\DailyMailUpdates;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Poly;
use SkylarkSoft\GoRMG\Iedroplets\Models\Shipment;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

class DailyProductionSummary
{
    private $date;

    private function __construct($date)
    {
        $this->date = $date;
        return $this;
    }

    public static function setDate($date): self
    {
        return new static($date);
    }

    private function getDate()
    {
        return $this->date;
    }

    private function getCuttingProduction()
    {
        return DateTableWiseCutProductionReport::query()
            ->with(['cuttingFloor', 'cuttingTable'])
            ->selectRaw('production_date,cutting_floor_id,cutting_table_id,SUM(cutting_qty) AS total_cutting_qty')
            ->where('production_date', $this->getDate())
            ->groupBy(['production_date', 'cutting_floor_id', 'cutting_table_id'])
            ->get()
            ->map(function ($collection) {
                return [
                    'floor_no' => $collection->cuttingFloor->floor_no,
                    'table_no' => $collection->cuttingTable->table_no,
                    'production' => $collection->total_cutting_qty,
                ];
            });
    }

    private function getPrintProduction()
    {
        return DateAndColorWiseProduction::query()
            ->selectRaw('production_date,SUM(print_sent_qty) AS total_print_sent_qty,'
                . 'SUM(print_received_qty) AS total_print_received_qty,'
                . 'SUM(embroidary_sent_qty) AS total_embroidary_sent_qty,'
                . 'SUM(embroidary_received_qty) AS total_embroidary_received_qty'
            )
            ->where('production_date', $this->getDate())
            ->groupBy(['production_date'])
            ->first();
    }

    private function getSewingProduction(): array
    {
        $sewingProduction = FinishingProductionReport::query()
            ->with(['floorWithoutGlobalScopes'])
            ->selectRaw('production_date,floor_id,SUM(sewing_input) AS total_sewing_input,SUM(sewing_output) AS total_sewing_output')
            ->where('production_date', $this->getDate())
            ->groupBy(['production_date', 'floor_id'])
            ->get();

        $sewingInProduction = $sewingProduction->map(function ($collection) {
            return [
                'floor_no' => $collection->floorWithoutGlobalScopes->floor_no,
                'production' => $collection->total_sewing_input,
            ];
        });

        $sewingOutProduction = $sewingProduction->map(function ($collection) {
            return [
                'floor_no' => $collection->floorWithoutGlobalScopes->floor_no,
                'production' => $collection->total_sewing_output,
            ];
        });

        return [
            'sewingInProduction' => $sewingInProduction,
            'sewingOutProduction' => $sewingOutProduction,
        ];
    }

    private function getFinishingProduction()
    {
        if (GarmentsProductionEntry::query()->first()['finishing_report'] == 'iron_poly_packing') {
            $finishingProduction = Poly::query()
                ->with('finishingFloor')
                ->whereDate('production_date', $this->getDate())
                ->groupBy(['production_date', 'finishing_floor_id'])
                ->get()
                ->map(function ($collection) {
                    return [
                        'floor_no' => $collection->finishingFloor->name,
                        'production' => $collection->poly_qty,
                    ];
                });
        } else {
            $finishingProduction = HourWiseFinishingProduction::query()
                ->with(['floor'])
                ->selectRaw('production_date,finishing_floor_id,'
                    . 'SUM(hour_0+hour_1+hour_2+hour_3+hour_4+hour_5+hour_6+'
                    . 'hour_7+hour_8+hour_9+hour_10+hour_11+hour_12+hour_13+'
                    . 'hour_14+hour_15+hour_16+hour_17+hour_18+hour_19+hour_20+'
                    . 'hour_21+hour_22+hour_23) AS total_poly'
                )
                ->where('production_date', $this->getDate())
                ->where('production_type', HourWiseFinishingProduction::POLY)
                ->groupBy(['production_date', 'finishing_floor_id', 'production_type'])
                ->get()
                ->map(function ($collection) {
                    return [
                        'floor_no' => $collection->floor->name,
                        'production' => $collection->total_poly,
                    ];
                });
        }


        if (!$finishingProduction->count()) {
            $finishingProduction = DateAndColorWiseProduction::query()
                ->selectRaw('production_date,SUM(poly_qty) AS total_poly_qty')
                ->where('production_date', $this->getDate())
                ->groupBy(['production_date'])
                ->get()
                ->map(function ($collection) {
                    return [
                        'floor_no' => '',
                        'production' => $collection->total_poly_qty,
                    ];
                });
        }

        return $finishingProduction;
    }

    public function generateSummary(): array
    {
        $cuttingProduction = $this->getCuttingProduction();
        $printProduction = $this->getPrintProduction();
        $sewingProduction = $this->getSewingProduction();
        $finishingProduction = $this->getFinishingProduction();
        $shipment = Shipment::todayShipmentQty();

        return [
            'cutting' => collect($cuttingProduction)->groupBy(['floor_no', 'table_name']),
            'cutting_rows' => collect($cuttingProduction)->count(),
            'cutting_total' => $cuttingProduction->sum('production'),
            'print' => $printProduction,
            'sewing_input' => $sewingProduction['sewingInProduction'],
            'total_sewing_input' => collect($sewingProduction['sewingInProduction'])->sum('production'),
            'sewing_output' => $sewingProduction['sewingOutProduction'],
            'total_sewing_output' => collect($sewingProduction['sewingOutProduction'])->sum('production'),
            'finishing' => $finishingProduction,
            'total_finishing' => collect($finishingProduction)->sum('production'),
            'shipment' => $shipment,
        ];
    }
}
