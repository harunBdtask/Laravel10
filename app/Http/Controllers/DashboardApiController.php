<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;

class DashboardApiController extends UtitlityController
{
    public function getUserWiseOrder(): JsonResponse
    {
        $data = User::query()->withCount('order')->having('order_count', '>', 0)->get();

        $colors = array();
        foreach ($data as $key => $value) {
            $colors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }

        $response = [
            'labels' => $data->pluck('full_name'),
            'data' => $data->pluck('order_count'),
            'colors' => $colors
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function getGarmentProductionSummaryData(): array
    {
        $dashboardRelatedQuery = DateAndColorWiseProduction::query()
            ->where('production_date', '>=', $this->lastYearDate)
            ->where('production_date', '<=', $this->todayDate);

        $todayQuery = clone $dashboardRelatedQuery;
        $todayData = $todayQuery->whereDate('production_date', $this->todayDate)
            ->selectRaw('
                SUM(cutting_qty) as todayCutting,
                SUM(cutting_rejection_qty) as todayCuttingRejection,
                SUM(print_sent_qty) as todayPrintSent,
                SUM(print_received_qty) as todayPrintReceived,
                SUM(print_rejection_qty) as todayPrintRejection,
                SUM(embroidary_sent_qty) as todayEmbrSent,
                SUM(embroidary_received_qty) as todayEmbrReceived,
                SUM(embroidary_rejection_qty) as todayEmbrRejection,
                SUM(input_qty) as todayInput,
                SUM(sewing_output_qty) as todayOutput,
                SUM(sewing_rejection_qty) as todaySewingRejection,
                SUM(washing_sent_qty) as todayWashingSent,
                SUM(washing_received_qty) as todayWashingReceived,
                SUM(washing_rejection_qty) as todayWashingRejection,
                SUM(poly_qty) as todayFinishing,
                SUM(poly_rejection) as todayFinishingRejection,
                SUM(ship_qty) as todayShipQuantity,
                SUM(iron_qty) as todayIronQuantity,
                (SUM(cutting_rejection_qty) + SUM(print_rejection_qty) + SUM(embroidary_rejection_qty) + SUM(washing_rejection_qty) + SUM(poly_rejection) + SUM(iron_rejection_qty) + SUM(packing_rejection_qty))  as todayRejectionQuantity

            ')->first();

        $lastDayQuery = clone $dashboardRelatedQuery;
        $lastDayData = $lastDayQuery->whereDate('production_date', $this->yesterdayDate)
            ->selectRaw('
                SUM(cutting_qty) as lastDayCutting,
                SUM(cutting_rejection_qty) as lastDayCuttingRejection,
                SUM(print_sent_qty) as lastDayPrintSent,
                SUM(print_received_qty) as lastDayPrintReceived,
                SUM(print_rejection_qty) as lastDayPrintRejection,
                SUM(embroidary_sent_qty) as lastDayEmbrSent,
                SUM(embroidary_received_qty) as lastDayEmbrReceived,
                SUM(embroidary_rejection_qty) as lastDayEmbrRejection,
                SUM(input_qty) as lastDayInput,
                SUM(sewing_output_qty) as lastDayOutput,
                SUM(sewing_rejection_qty) as lastDaySewingRejection,
                SUM(washing_sent_qty) as lastDayWashingSent,
                SUM(washing_received_qty) as lastDayWashingReceived,
                SUM(washing_rejection_qty) as lastDayWashingRejection,
                SUM(poly_qty) as lastDayFinishing,
                SUM(poly_rejection) as lastDayFinishingRejection,
                SUM(ship_qty) as lastDayShipQuantity,
                SUM(iron_qty) as lastDayIronQuantity,
                (SUM(cutting_rejection_qty) + SUM(print_rejection_qty) + SUM(embroidary_rejection_qty) + SUM(washing_rejection_qty) + SUM(poly_rejection) + SUM(iron_rejection_qty) + SUM(packing_rejection_qty))  as lastDayRejectionQuantity

            ')->first();

        $thisWeekQuery = clone $dashboardRelatedQuery;
        $thisWeekData = $thisWeekQuery->whereDate('production_date', '>=', $this->thisWeekStartDate)
            ->whereDate('production_date', '<=', $this->thisWeekEndDate)
            ->selectRaw('
                SUM(cutting_qty) as thisWeekCutting,
                SUM(cutting_rejection_qty) as thisWeekCuttingRejection,
                SUM(print_sent_qty) as thisWeekPrintSent,
                SUM(print_received_qty) as thisWeekPrintReceived,
                SUM(print_rejection_qty) as thisWeekPrintRejection,
                SUM(embroidary_sent_qty) as thisWeekEmbrSent,
                SUM(embroidary_received_qty) as thisWeekEmbrReceived,
                SUM(embroidary_rejection_qty) as thisWeekEmbrRejection,
                SUM(input_qty) as thisWeekInput,
                SUM(sewing_output_qty) as thisWeekOutput,
                SUM(sewing_rejection_qty) as thisWeekSewingRejection,
                SUM(washing_sent_qty) as thisWeekWashingSent,
                SUM(washing_received_qty) as thisWeekWashingReceived,
                SUM(washing_rejection_qty) as thisWeekWashingRejection,
                SUM(poly_qty) as thisWeekFinishing,
                SUM(poly_rejection) as thisWeekFinishingRejection,
                SUM(ship_qty) as thisWeekShipQuantity,
                SUM(iron_qty) as thisWeekIronQuantity,
                (SUM(cutting_rejection_qty) + SUM(print_rejection_qty) + SUM(embroidary_rejection_qty) + SUM(washing_rejection_qty) + SUM(poly_rejection) + SUM(iron_rejection_qty) + SUM(packing_rejection_qty))  as thisWeekRejectionQuantity

            ')->first();

        $lastWeekQuery = clone $dashboardRelatedQuery;
        $lastWeekData = $lastWeekQuery->whereDate('production_date', '>=', $this->lastWeekStartDate)
            ->whereDate('production_date', '<=', $this->lastWeekEndDate)
            ->selectRaw('
                SUM(cutting_qty) as lastWeekCutting,
                SUM(cutting_rejection_qty) as lastWeekCuttingRejection,
                SUM(print_sent_qty) as lastWeekPrintSent,
                SUM(print_received_qty) as lastWeekPrintReceived,
                SUM(print_rejection_qty) as lastWeekPrintRejection,
                SUM(embroidary_sent_qty) as lastWeekEmbrSent,
                SUM(embroidary_received_qty) as lastWeekEmbrReceived,
                SUM(embroidary_rejection_qty) as lastWeekEmbrRejection,
                SUM(input_qty) as lastWeekInput,
                SUM(sewing_output_qty) as lastWeekOutput,
                SUM(sewing_rejection_qty) as lastWeekSewingRejection,
                SUM(washing_sent_qty) as lastWeekWashingSent,
                SUM(washing_received_qty) as lastWeekWashingReceived,
                SUM(washing_rejection_qty) as lastWeekWashingRejection,
                SUM(poly_qty) as lastWeekFinishing,
                SUM(poly_rejection) as lastWeekFinishingRejection,
                SUM(ship_qty) as lastWeekShipQuantity,
                SUM(iron_qty) as lastWeekIronQuantity,
                (SUM(cutting_rejection_qty) + SUM(print_rejection_qty) + SUM(embroidary_rejection_qty) + SUM(washing_rejection_qty) + SUM(poly_rejection) + SUM(iron_rejection_qty) + SUM(packing_rejection_qty))  as lastWeekRejectionQuantity
            ')->first();

        $thisMonthQuery = clone $dashboardRelatedQuery;
        $thisMonthData = $thisMonthQuery->whereDate('production_date', '>=', $this->thisMonthStartDate)
            ->whereDate('production_date', '<=', $this->thisMonthEndDate)
            ->selectRaw('
                SUM(cutting_qty) as thisMonthCutting,
                SUM(cutting_rejection_qty) as thisMonthCuttingRejection,
                SUM(print_sent_qty) as thisMonthPrintSent,
                SUM(print_received_qty) as thisMonthPrintReceived,
                SUM(print_rejection_qty) as thisMonthPrintRejection,
                SUM(embroidary_sent_qty) as thisMonthEmbrSent,
                SUM(embroidary_received_qty) as thisMonthEmbrReceived,
                SUM(embroidary_rejection_qty) as thisMonthEmbrRejection,
                SUM(input_qty) as thisMonthInput,
                SUM(sewing_output_qty) as thisMonthOutput,
                SUM(sewing_rejection_qty) as thisMonthSewingRejection,
                SUM(washing_sent_qty) as thisMonthWashingSent,
                SUM(washing_received_qty) as thisMonthWashingReceived,
                SUM(washing_rejection_qty) as thisMonthWashingRejection,
                SUM(poly_qty) as thisMonthFinishing,
                SUM(poly_rejection) as thisMonthFinishingRejection,
                SUM(ship_qty) as thisMonthShipQuantity,
                SUM(iron_qty) as thisMonthIronQuantity,
                (SUM(cutting_rejection_qty) + SUM(print_rejection_qty) + SUM(embroidary_rejection_qty) + SUM(washing_rejection_qty) + SUM(poly_rejection) + SUM(iron_rejection_qty) + SUM(packing_rejection_qty))  as thisMonthRejectionQuantity

            ')->first();

        $lastMonthQuery = clone $dashboardRelatedQuery;
        $lastMonthData = $lastMonthQuery->whereDate('production_date', '>=', $this->lastMonthStartDate)
            ->whereDate('production_date', '<=', $this->lastMonthEndDate)
            ->selectRaw('
                SUM(cutting_qty) as lastMonthCutting,
                SUM(cutting_rejection_qty) as lastMonthCuttingRejection,
                SUM(print_sent_qty) as lastMonthPrintSent,
                SUM(print_received_qty) as lastMonthPrintReceived,
                SUM(print_rejection_qty) as lastMonthPrintRejection,
                SUM(embroidary_sent_qty) as lastMonthEmbrSent,
                SUM(embroidary_received_qty) as lastMonthEmbrReceived,
                SUM(embroidary_rejection_qty) as lastMonthEmbrRejection,
                SUM(input_qty) as lastMonthInput,
                SUM(sewing_output_qty) as lastMonthOutput,
                SUM(sewing_rejection_qty) as lastMonthSewingRejection,
                SUM(washing_sent_qty) as lastMonthWashingSent,
                SUM(washing_received_qty) as lastMonthWashingReceived,
                SUM(washing_rejection_qty) as lastMonthWashingRejection,
                SUM(poly_qty) as lastMonthFinishing,
                SUM(poly_rejection) as lastMonthFinishingRejection,
                SUM(ship_qty) as lastMonthShipQuantity,
                SUM(iron_qty) as lastMonthIronQuantity,
                (SUM(cutting_rejection_qty) + SUM(print_rejection_qty) + SUM(embroidary_rejection_qty) + SUM(washing_rejection_qty) + SUM(poly_rejection) + SUM(iron_rejection_qty) + SUM(packing_rejection_qty))  as lastMonthRejectionQuantity
            ')->first();

        $lastSixMonthQuery = clone $dashboardRelatedQuery;
        $lastSixMonthData = $lastSixMonthQuery->whereDate('production_date', '>=', $this->lastSixMonthDate)
            ->whereDate('production_date', '<=', $this->todayDate)
            ->selectRaw('
                SUM(cutting_qty) as lastSixMonthCutting,
                SUM(cutting_rejection_qty) as lastSixMonthCuttingRejection,
                SUM(print_sent_qty) as lastSixMonthPrintSent,
                SUM(print_received_qty) as lastSixMonthPrintReceived,
                SUM(print_rejection_qty) as lastSixMonthPrintRejection,
                SUM(embroidary_sent_qty) as lastSixMonthEmbrSent,
                SUM(embroidary_received_qty) as lastSixMonthEmbrReceived,
                SUM(embroidary_rejection_qty) as lastSixMonthEmbrRejection,
                SUM(input_qty) as lastSixMonthInput,
                SUM(sewing_output_qty) as lastSixMonthOutput,
                SUM(sewing_rejection_qty) as lastSixMonthSewingRejection,
                SUM(washing_sent_qty) as lastSixMonthWashingSent,
                SUM(washing_received_qty) as lastSixMonthWashingReceived,
                SUM(washing_rejection_qty) as lastSixMonthWashingRejection,
                SUM(poly_qty) as lastSixMonthFinishing,
                SUM(poly_rejection) as lastSixMonthFinishingRejection,
                SUM(ship_qty) as lastSixMonthShipQuantity,
                SUM(iron_qty) as lastSixMonthIronQuantity,
                (SUM(cutting_rejection_qty) + SUM(print_rejection_qty) + SUM(embroidary_rejection_qty) + SUM(washing_rejection_qty) + SUM(poly_rejection) + SUM(iron_rejection_qty) + SUM(packing_rejection_qty))  as lastSixMonthRejectionQuantity
            ')->first();

        $lastYearQuery = clone $dashboardRelatedQuery;
        $lastYearData = $lastYearQuery->selectRaw('
                SUM(cutting_qty) as lastYearCutting,
                SUM(cutting_rejection_qty) as lastYearCuttingRejection,
                SUM(print_sent_qty) as lastYearPrintSent,
                SUM(print_received_qty) as lastYearPrintReceived,
                SUM(print_rejection_qty) as lastYearPrintRejection,
                SUM(embroidary_sent_qty) as lastYearEmbrSent,
                SUM(embroidary_received_qty) as lastYearEmbrReceived,
                SUM(embroidary_rejection_qty) as lastYearEmbrRejection,
                SUM(input_qty) as lastSixMonthInput,
                SUM(sewing_output_qty) as lastYearOutput,
                SUM(sewing_rejection_qty) as lastYearSewingRejection,
                SUM(washing_sent_qty) as lastYearWashingSent,
                SUM(washing_received_qty) as lastYearWashingReceived,
                SUM(washing_rejection_qty) as lastYearWashingRejection,
                SUM(poly_qty) as lastYearFinishing,
                SUM(poly_rejection) as lastYearFinishingRejection,
                SUM(ship_qty) as lastYearShipQuantity,
                SUM(iron_qty) as lastYearIronQuantity,
                (SUM(cutting_rejection_qty) + SUM(print_rejection_qty) + SUM(embroidary_rejection_qty) + SUM(washing_rejection_qty) + SUM(poly_rejection) + SUM(iron_rejection_qty) + SUM(packing_rejection_qty))  as lastYearRejectionQuantity
            ')->first();

        return [
            'todayCutting' => $todayData->todayCutting ?? 0,
            'lastDayCutting' => $lastDayData->lastDayCutting ?? 0,
            'thisWeekCutting' => $thisWeekData->thisWeekCutting ?? 0,
            'thisMonthCutting' => $thisMonthData->thisMonthCutting ?? 0,
            'lastSixMonthCutting' => $lastSixMonthData->lastSixMonthCutting ?? 0,
            'lastYearCutting' => $lastYearData->lastYearCutting ?? 0,

            /* cutting rejection */
            'todayCuttingRejection' => $todayData->todayCuttingRejection ?? 0,
            'lastDayCuttingRejection' => $lastDayData->lastDayCuttingRejection ?? 0,
            'thisWeekCuttingRejection' => $thisWeekData->thisWeekCuttingRejection ?? 0,
            'thisMonthCuttingRejection' => $thisMonthData->thisMonthCuttingRejection ?? 0,
            'lastSixMonthCuttingRejection' => $lastSixMonthData->lastSixMonthCuttingRejection ?? 0,
            'lastYearCuttingRejection' => $lastYearData->lastYearCuttingRejection ?? 0,

            /* print sent */
            'todayPrintSent' => $todayData->todayPrintSent ?? 0,
            'lastDayPrintSent' => $lastDayData->lastDayPrintSent ?? 0,
            'thisWeekPrintSent' => $thisWeekData->thisWeekPrintSent ?? 0,
            'thisMonthPrintSent' => $thisMonthData->thisMonthPrintSent ?? 0,
            'lastSixMonthPrintSent' => $lastSixMonthData->lastSixMonthPrintSent ?? 0,
            'lastYearPrintSent' => $lastYearData->lastYearPrintSent ?? 0,

            /* print received */
            'todayPrintReceived' => $todayData->todayPrintReceived ?? 0,
            'lastDayPrintReceived' => $lastDayData->lastDayPrintReceived ?? 0,
            'thisWeekPrintReceived' => $thisWeekData->thisWeekPrintReceived ?? 0,
            'thisMonthPrintReceived' => $thisMonthData->thisMonthPrintReceived ?? 0,
            'lastSixMonthPrintReceived' => $lastSixMonthData->lastSixMonthPrintReceived ?? 0,
            'lastYearPrintReceived' => $lastYearData->lastYearPrintReceived ?? 0,

            /* print rejection */
            'todayPrintRejection' => $todayData->todayPrintRejection ?? 0,
            'lastDayPrintRejection' => $lastDayData->lastDayPrintRejection ?? 0,
            'thisWeekPrintRejection' => $thisWeekData->thisWeekPrintRejection ?? 0,
            'thisMonthPrintRejection' => $thisMonthData->thisMonthPrintRejection ?? 0,
            'lastSixMonthPrintRejection' => $lastSixMonthData->lastSixMonthPrintRejection ?? 0,
            'lastYearPrintRejection' => $lastYearData->lastYearPrintRejection ?? 0,

            /* embr sent */
            'todayEmbrSent' => $todayData->todayEmbrSent ?? 0,
            'lastDayEmbrSent' => $lastDayData->lastDayEmbrSent ?? 0,
            'thisWeekEmbrSent' => $thisWeekData->thisWeekEmbrSent ?? 0,
            'thisMonthEmbrSent' => $thisMonthData->thisMonthEmbrSent ?? 0,
            'lastSixMonthEmbrSent' => $lastSixMonthData->lastSixMonthEmbrSent ?? 0,
            'lastYearEmbrSent' => $lastYearData->lastYearEmbrSent ?? 0,

            /* embr received rejection */
            'todayEmbrReceived' => $todayData->todayEmbrReceived ?? 0,
            'lastDayEmbrReceived' => $lastDayData->lastDayEmbrReceived ?? 0,
            'thisWeekEmbrReceived' => $thisWeekData->thisWeekEmbrReceived ?? 0,
            'thisMonthEmbrReceived' => $thisMonthData->thisMonthEmbrReceived ?? 0,
            'lastSixMonthEmbrReceived' => $lastSixMonthData->lastSixMonthEmbrReceived ?? 0,
            'lastYearEmbrReceived' => $lastYearData->lastYearEmbrReceived ?? 0,

            /* embr rejection */
            'todayEmbrRejection' => $todayData->todayEmbrRejection ?? 0,
            'lastDayEmbrRejection' => $lastDayData->lastDayEmbrRejection ?? 0,
            'thisWeekEmbrRejection' => $thisWeekData->thisWeekEmbrRejection ?? 0,
            'thisMonthEmbrRejection' => $thisMonthData->thisMonthEmbrRejection ?? 0,
            'lastSixMonthEmbrRejection' => $lastSixMonthData->lastSixMonthEmbrRejection ?? 0,
            'lastYearEmbrRejection' => $lastYearData->lastYearEmbrRejection ?? 0,

            /* input */
            'lastDayInput' => $lastDayData->lastDayInput ?? 0,
            'thisWeekInput' => $thisWeekData->thisWeekInput ?? 0,
            'thisMonthInput' => $thisMonthData->thisMonthInput ?? 0,
            'lastSixMonthInput' => $lastSixMonthData->lastSixMonthInput ?? 0,
            'lastYearSewingInput' => $lastYearData->lastYearInput ?? 0,

            /* sewing output */
            'lastDayOutput' => $lastDayData->lastDayOutput ?? 0,
            'thisWeekOutput' => $thisWeekData->thisWeekOutput ?? 0,
            'thisMonthOutput' => $thisMonthData->thisMonthOutput ?? 0,
            'lastSixMonthOutput' => $lastSixMonthData->lastSixMonthOutput ?? 0,
            'lastYearSewingOutput' => $lastYearData->lastYearOutput ?? 0,

            /* sewing rejection */
            'todaySewingRejection' => $todayData->todaySewingRejection ?? 0,
            'lastDaySewingRejection' => $lastDayData->lastDaySewingRejection ?? 0,
            'thisWeekSewingRejection' => $thisWeekData->thisWeekSewingRejection ?? 0,
            'thisMonthSewingRejection' => $thisMonthData->thisMonthSewingRejection ?? 0,
            'lastSixMonthSewingRejection' => $lastSixMonthData->lastSixMonthSewingRejection ?? 0,
            'lastYearSewingRejection' => $lastYearData->lastYearSewingRejection ?? 0,

            /* washing sent */
            'todayWashingSent' => $todayData->todayWashingSent ?? 0,
            'lastDayWashingSent' => $lastDayData->lastDayWashingSent ?? 0,
            'thisWeekWashingSent' => $thisWeekData->thisWeekWashingSent ?? 0,
            'thisMonthWashingSent' => $thisMonthData->thisMonthWashingSent ?? 0,
            'lastSixMonthWashingSent' => $lastSixMonthData->lastSixMonthWashingSent ?? 0,
            'lastYearWashingSent' => $lastYearData->lastYearWashingSent ?? 0,

            /* washing received */
            'todayWashingReceived' => $todayData->todayWashingReceived ?? 0,
            'lastDayWashingReceived' => $lastDayData->lastDayWashingReceived ?? 0,
            'thisWeekWashingReceived' => $thisWeekData->thisWeekWashingReceived ?? 0,
            'thisMonthWashingReceived' => $thisMonthData->thisMonthWashingReceived ?? 0,
            'lastSixMonthWashingReceived' => $lastSixMonthData->lastSixMonthWashingReceived ?? 0,
            'lastYearWashingReceived' => $lastYearData->lastYearWashingReceived ?? 0,

            /* washing rejection */
            'todayWashingRejection' => $todayData->todayWashingRejection ?? 0,
            'lastDayWashingRejection' => $lastDayData->lastDayWashingRejection ?? 0,
            'thisWeekWashingRejection' => $thisWeekData->thisWeekWashingRejection ?? 0,
            'thisMonthWashingRejection' => $thisMonthData->thisMonthWashingRejection ?? 0,
            'lastSixMonthWashingRejection' => $lastSixMonthData->lastSixMonthWashingRejection ?? 0,
            'lastYearWashingRejection' => $lastYearData->lastYearWashingRejection ?? 0,

            /* finishing */
            'lastDayFinishing' => $lastDayData->lastDayFinishing ?? 0,
            'thisWeekFinishing' => $thisWeekData->thisWeekFinishing ?? 0,
            'lastWeekFinishing' => $lastWeekData->lastWeekFinishing ?? 0,
            'thisMonthFinishing' => $thisMonthData->thisMonthFinishing ?? 0,
            'lastSixMonthFinishing' => $lastSixMonthData->lastSixMonthFinishing ?? 0,
            'lastYearFinishing' => $lastYearData->lastYearFinishing ?? 0,

            /* finishing rejection */
            'todayFinishingRejection' => $todayData->todayFinishingRejection ?? 0,
            'lastDayFinishingRejection' => $lastDayData->lastDayFinishingRejection ?? 0,
            'thisWeekFinishingRejection' => $thisWeekData->thisWeekFinishingRejection ?? 0,
            'thisMonthFinishingRejection' => $thisMonthData->thisMonthFinishingRejection ?? 0,
            'lastSixMonthFinishingRejection' => $lastSixMonthData->lastSixMonthFinishingRejection ?? 0,
            'lastYearFinishingRejection' => $lastYearData->lastYearFinishingRejection ?? 0,

            /* Iron Quantity */
            'todayIronQuantity' => $todayData->todayIronQuantity ?? 0,
            'lastDayIronQuantity' => $lastDayData->lastDayIronQuantity ?? 0,
            'thisWeekIronQuantity' => $thisWeekData->thisWeekIronQuantity ?? 0,
            'thisMonthIronQuantity' => $thisMonthData->thisMonthIronQuantity ?? 0,
            'lastSixMonthIronQuantity' => $lastSixMonthData->lastSixMonthIronQuantity ?? 0,
            'lastYearIronQuantity' => $lastYearData->lastYearIronQuantity ?? 0,


            /* Ship Quantity */
            'todayShipQuantity' => $todayData->todayShipQuantity ?? 0,
            'lastDayShipQuantity' => $lastDayData->lastDayShipQuantity ?? 0,
            'thisWeekShipQuantity' => $thisWeekData->thisWeekShipQuantity ?? 0,
            'thisMonthShipQuantity' => $thisMonthData->thisMonthShipQuantity ?? 0,
            'lastSixMonthShipQuantity' => $lastSixMonthData->lastSixMonthShipQuantity ?? 0,
            'lastYearShipQuantity' => $lastYearData->lastYearShipQuantity ?? 0,

            /* Rejection Quantity */
            'todayRejectionQuantity' => $todayData->todayRejectionQuantity ?? 0,
            'lastDayRejectionQuantity' => $lastDayData->lastDayRejectionQuantity ?? 0,
            'thisWeekRejectionQuantity' => $thisWeekData->thisWeekRejectionQuantity ?? 0,
            'thisMonthRejectionQuantity' => $thisMonthData->thisMonthRejectionQuantity ?? 0,
            'lastSixMonthRejectionQuantity' => $lastSixMonthData->lastSixMonthRejectionQuantity ?? 0,
            'lastYearRejectionQuantity' => $lastYearData->lastYearRejectionQuantity ?? 0,
        ];
    }

    public function getGarmentProductionSummary(): JsonResponse
    {
        $response = $this->getGarmentProductionSummaryData();

        $purchase_order = PurchaseOrder::query()->where('country_ship_date', '>=', $this->lastYearDate)
            ->where('country_ship_date', '<=', $this->todayDate)
            ->selectRaw("count(id) AS total_data, DATE_FORMAT(country_ship_date, '%Y-%m') AS new_date")
            ->orderBy('new_date', 'ASC')
            ->groupBy('new_date')
            ->get()
            ->pluck('total_data')
            ->toArray();

        $garment_production = array();

        $garment_production['cutting']['level'] = ['Cutting Quantity', 'Cutting Rejection'];
        $garment_production['cutting']['today'] = [$response['todayCutting'], $response['todayCuttingRejection']];
        $garment_production['cutting']['lastDay'] = [$response['lastDayCutting'], $response['lastDayCuttingRejection']];
        $garment_production['cutting']['thisMonth'] = [$response['thisMonthCutting'], $response['thisMonthCuttingRejection']];
        $garment_production['cutting']['lastSixMonth'] = [$response['lastSixMonthCutting'], $response['lastSixMonthCuttingRejection']];
        $garment_production['cutting']['lastYearMonth'] = [$response['lastYearCutting'], $response['lastYearCuttingRejection']];

        $garment_production['print']['level'] = ['Print Sent', 'Print Receive', 'Print Rejection'];
        $garment_production['print']['today'] = [$response['todayPrintSent'], $response['todayPrintReceived'], $response['todayPrintRejection']];
        $garment_production['print']['lastDay'] = [$response['lastDayPrintSent'], $response['lastDayPrintReceived'], $response['lastDayPrintRejection']];
        $garment_production['print']['thisMonth'] = [$response['thisMonthPrintSent'], $response['thisMonthPrintReceived'], $response['thisMonthPrintRejection']];
        $garment_production['print']['lastSixMonth'] = [$response['lastSixMonthPrintSent'], $response['lastSixMonthPrintReceived'], $response['lastSixMonthPrintRejection']];
        $garment_production['print']['lastYearMonth'] = [$response['lastYearPrintSent'], $response['lastYearPrintReceived'], $response['lastYearPrintRejection']];

        $garment_production['embroidary']['level'] = ['Embroidery Sent', 'Embroidery Receive', 'Embroidery Rejection'];
        $garment_production['embroidary']['today'] = [$response['todayEmbrSent'], $response['todayEmbrReceived'], $response['todayEmbrRejection']];
        $garment_production['embroidary']['lastDay'] = [$response['lastDayEmbrSent'], $response['lastDayEmbrReceived'], $response['lastDayEmbrRejection']];
        $garment_production['embroidary']['thisMonth'] = [$response['thisMonthEmbrSent'], $response['thisMonthEmbrReceived'], $response['thisMonthEmbrRejection']];
        $garment_production['embroidary']['lastSixMonth'] = [$response['lastSixMonthEmbrSent'], $response['lastSixMonthEmbrReceived'], $response['lastSixMonthEmbrRejection']];
        $garment_production['embroidary']['lastYearMonth'] = [$response['lastYearEmbrSent'], $response['lastYearEmbrReceived'], $response['lastYearEmbrRejection']];

        $garment_production['washing']['level'] = ['Washing Sent', 'Washing Receive', 'Washing Rejection'];
        $garment_production['washing']['today'] = [$response['todayWashingSent'], $response['todayWashingReceived'], $response['todayWashingRejection']];
        $garment_production['washing']['lastDay'] = [$response['lastDayWashingSent'], $response['lastDayWashingReceived'], $response['lastDayWashingRejection']];
        $garment_production['washing']['thisMonth'] = [$response['thisMonthWashingSent'], $response['thisMonthWashingReceived'], $response['thisMonthWashingRejection']];
        $garment_production['washing']['lastSixMonth'] = [$response['lastSixMonthWashingSent'], $response['lastSixMonthWashingReceived'], $response['lastSixMonthWashingRejection']];
        $garment_production['washing']['lastYearMonth'] = [$response['lastYearWashingSent'], $response['lastYearWashingReceived'], $response['lastYearWashingRejection']];


        return response()->json([
            'garment_production' => $garment_production,
            'recep_summary' => $purchase_order,
        ]);
    }
}
