<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\FinishingTarget;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Poly;
use SkylarkSoft\GoRMG\Iedroplets\Models\CuttingTarget;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Sample\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Department;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class UtitlityController extends Controller
{
    public $todayDate;
    public $yesterdayDate;
    public $thisWeekStartDate;
    public $thisWeekEndDate;
    public $lastWeekStartDate;
    public $lastWeekEndDate;
    public $thisMonthStartDate;
    public $thisMonthEndDate;
    public $lastMonthStartDate;
    public $lastMonthEndDate;
    public $thisMonthNo;
    public $thisYearNo;
    public $lastYearDate;
    public $lastSixMonthDate;
    public $lastSevenDayEnd;

    public function __construct()
    {
        $this->todayDate = Carbon::today()->format('Y-m-d');
        $this->yesterdayDate = Carbon::yesterday()->format('Y-m-d');
        $this->thisWeekStartDate = Carbon::today()->startOfWeek()->format('Y-m-d');
        $this->thisWeekEndDate = Carbon::today()->endOfWeek()->format('Y-m-d');
        $this->lastWeekStartDate = Carbon::today()->startOfWeek()->subDay()->startOfWeek()->format('Y-m-d');
        $this->lastWeekEndDate = Carbon::today()->startOfWeek()->subDay()->endOfWeek()->format('Y-m-d');
        $this->thisMonthStartDate = Carbon::today()->startOfMonth()->format('Y-m-d');
        $this->thisMonthEndDate = Carbon::today()->endOfMonth()->format('Y-m-d');
        $this->lastMonthStartDate = Carbon::today()->startOfMonth()->subDay()->startOfMonth()->format('Y-m-d');
        $this->lastMonthEndDate = Carbon::today()->startOfMonth()->subDay()->endOfMonth()->format('Y-m-d');
        $this->thisMonthNo = Carbon::now()->month;
        $this->thisYearNo = Carbon::now()->year;
        $this->lastSevenDayEnd = Carbon::now()->subDay(7)->format('Y-m-d');
        $this->lastYearDate = Carbon::now()->subYear(1)->format('Y-m-d');
        $this->lastSixMonthDate = Carbon::now()->subMonth(6)->format('Y-m-d');
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function menuCheck()
    {
        return Session::get('menu');
    }

    public function flush()
    {
        \session()->flush();
    }

    public function factoryDashboard(Request $request)
    {
        $factoryId = $request->factory_id;
        if ($factoryId) {
            Session::forget('factoryId');
            Session::put('factoryId', $factoryId);
        }
        return redirect()->back();
    }

    public function dashboard()
    {
        $data['totalBuyers'] = $this->buyerCount();
        $data['totalOrders'] = $this->orderCount();
        $data['totalBudgets'] = $this->budgetCount();
        $data['totalPO'] = $this->poCount();
        $data['totalUsers'] = $this->userCount();
        $data['totalPriceQuotation'] = $this->priceQuotationCount();
        $data['fabricBookings'] = $this->fabricBookingCount();
        $data['shortFabricBookings'] = $this->shortFabricBookingCount();
        $data['trimsBookings'] = $this->trimsBookingCount();
        $data['shortTrimsBookings'] = $this->shortTrimsBookingCount();
        $data['target_and_achievement'] = $this->getProductionTargetAchievementData();
        $data['is_approved_dashboard'] = 0;
        return view('pages.dashboard2', $data);
    }


    public function ApprovedDashboard()
    {
        $data['totalBuyers'] = $this->buyerCount();
        $data['totalOrders'] = $this->orderCount(true);
        $data['totalBudgets'] = $this->budgetCount(true);
        $data['totalPO'] = $this->poCount(true);
        $data['totalUsers'] = $this->userCount();
        $data['totalPriceQuotation'] = $this->priceQuotationCount(true);
        $data['fabricBookings'] = $this->fabricBookingCount(true);
        $data['shortFabricBookings'] = $this->shortFabricBookingCount(true);
        $data['trimsBookings'] = $this->trimsBookingCount(true);
        $data['shortTrimsBookings'] = $this->shortTrimsBookingCount(true);
        $data['target_and_achievement'] = $this->getProductionTargetAchievementData();
        $data['is_approved_dashboard'] = 1;
        return view('pages.dashboard2', $data);
    }

    private function buyerCount()
    {
        return Cache::remember('buyers_count', 240, function () {
            return Buyer::where('status', 'Active')->where('factory_id', factoryId())->count();
        });
    }

    private function orderCount($isApproved = false)
    {
        return 1;
    }

    private function budgetCount($isApproved = false)
    {
        return 2;
    }

    private function poCount($isApproved = false)
    {
        return 3;
    }

    private function userCount()
    {
        return Cache::remember('users_count', 240, function () {
            return User::count();
        });
    }

    private function priceQuotationCount($isApproved = false)
    {
        return 4;
    }

    private function fabricBookingCount($isApproved = false)
    {
        return 5;
    }

    private function shortFabricBookingCount($isApproved = false)
    {
        return 6;
    }

    private function trimsBookingCount($isApproved = false)
    {
        return 7;
    }

    private function shortTrimsBookingCount($isApproved = false)
    {
        return 8;
    }

    private function getProductionTargetAchievementData(): array
    {
       
        // Today Hourly Sewing Production Target vs Achievement
        $hours = [
            1 => 'hour_8',
            2 => 'hour_9',
            3 => 'hour_10',
            4 => 'hour_11',
            5 => 'hour_12',
            6 => 'hour_14',
            7 => 'hour_15',
            8 => 'hour_16',
            9 => 'hour_17',
            10 => 'hour_18'
        ];

        return [
            'cutting' => [
                'target' => 1,
                'achievement' => 2,
                'percentage' => getPercentage(1, 2)
            ],
            'sewing' => [
                'target' =>  0,
                'achievement' =>  0,
                'percentage' => getPercentage( 0, 0),
                'seven_day_target' => [],
                'seven_day_achievement' => []
            ],
            'knitting' => [
                'target' => 0,
                'achievement' => 0,
                'percentage' => 0,
            ],
            'finishing' => [
                'target' => round(2),
                'achievement' => round(3),
                'percentage' => round(getPercentage(4, 5)),
            ]
        ];
    }

    public function profileTest()
    {
        $data['user'] = User::where('id', \auth()->user()->id)->first();
        $data['random_users'] = User::where('id', '!=', \auth()->user()->id)->where('factory_id', factoryId())->limit(10)->inRandomOrder()->get();
        $data['users'] = User::where('id', '!=', \auth()->user()->id)->where('factory_id', factoryId())->get();
        $data['user_department'] = Department::where('id', \auth()->user()->department)->pluck('department_name');

        return view('partials.profile', $data);
    }

    public function singleProfileTest($id)
    {
        $data['user'] = User::where('id', $id)->first();
        $data['random_users'] = User::where('id', '!=', $id)->where('factory_id', factoryId())->limit(10)->inRandomOrder()->get();
        $data['users'] = User::where('id', '!=', $id)->where('factory_id', factoryId())->get();
        $data['user_department'] = Department::where('id', $id)->pluck('department_name');

        return view('partials.singleProfile', $data);
    }

    public function getOrderWiseProduction()
    {
        $orders = DB::table('orders')->get();
        $dateS = Carbon::now()->startOfMonth();
        $dateE = Carbon::now()->toDateTimeString();
        $result = [];
        foreach ($orders as $key => $order) {
            $result[$key]['corder'] = $order->order_no;
            $result[$key]['order_qty'] = isset($order->total_quantity) ? $order->total_quantity : 0;
            $result[$key]['assign_qty'] = DB::table('orders')
                ->leftJoin('knit_cards', 'knit_cards.order_id', '=', 'orders.id')
                ->where('knit_cards.order_id', $order->id)
                ->where('knit_cards.assign_qty', '!=', null)
                ->whereBetween('knit_cards.created_at', [$dateS, $dateE])
                ->sum('knit_cards.assign_qty');
        }

        return $result;
    }

    // New Code For Dashboard Data fetching
    public function protrackerRelatedData()
    {
        $dashboardRelatedQuery = DateAndColorWiseProduction::where('production_date', '>=', $this->lastYearDate)
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


        return [
            'todayCutting' => $todayData->todayCutting ?? 0,
            'todayInput' => $todayData->todayInput ?? 0,
            'todayFinishing' => $this->todayFinishingProduction() ?? 0,
            'todayOutput' => $todayData->todayOutput ?? 0,
        ];
    }

    public function test()
    {
        return view('partials.error');
    }

    public function dashboardData(): JsonResponse
    {
        $response = $this->protrackerRelatedData();

        return response()->json([
            'todayInput' => $response['todayInput'],
            'todayOutput' => $response['todayOutput'],
            'todayCutting' => $response['todayCutting'],
            'todayFinishing' => $response['todayFinishing'],
        ]);
    }

    /**
     * @return mixed
     */
    public function todayFinishingProduction()
    {
        if (finishingReportVariable() == 'iron_poly_packing') {
            return Poly::query()
                ->whereDate('production_date', date('Y-m-d'))
                ->sum('poly_qty');
        }
        return round(HourWiseFinishingProduction::query()
            ->selectRaw(DB::raw('
            SUM(hour_0 + hour_1 + hour_2
            + hour_3
            + hour_4
            + hour_5
            + hour_6
            + hour_7
            + hour_8
            + hour_9
            + hour_10
            + hour_11
            + hour_12
            + hour_13
            + hour_14
            + hour_15
            + hour_16
            + hour_17
            + hour_18
            + hour_19
            + hour_20
            + hour_21
            + hour_22
            + hour_23) as totalProduction'))
            ->whereDate('production_date', date('Y-m-d'))
            ->where('production_type', HourWiseFinishingProduction::PACKING)
            ->groupBy('production_date')
            ->get()
            ->sum('totalProduction'));
    }

    public function getNotification(): JsonResponse
    {
        $notifications = auth()->user()->notifications()->orderByDesc('created_at')->paginate(10);

        $response = [
            'data' => $notifications,
            'view' => view('skeleton::partials.notification', compact('notifications'))->render()
        ];

        return response()->json($response);
    }

    public function readNotification($id)
    {
        $notification = Notification::query()->find($id);
        $notification->markAsRead();
        return redirect($notification->data['url']);
    }

    public function markAllNotificationAsRead(): RedirectResponse
    {
        User::query()
            ->where('id', userId())
            ->first()
            ->unreadNotifications->markAsRead();

        return back();
    }
}
