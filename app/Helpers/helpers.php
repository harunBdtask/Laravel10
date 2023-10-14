<?php

use App\Services\MoneyFormatter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Skeleton\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

/**
 * Define system constants
 */
define('SUPER_ADMIN', 'super-admin');
define('S_DELETE_MSG', 'Successfully deleted');
define('E_DELETE_MSG', 'Not successfully updated');
define('S_UPDATE_MSG', 'Successfully updated');
define('E_UPDATE_MSG', 'Not successfully updated');
define('S_SAVE_MSG', 'Successfully created');
define('E_SAVE_MSG', 'Not successfully created');
define('S_DEL_MSG', 'Successfully deleted');
define('E_DEL_MSG', 'Not successfully deleted');

define('VALIDATION_FAIL', 403);
define('SUCCESS', 200);
define('FAIL', 500);
define('ACTIVE', 1);
define('INACTIVE', 0);
define('CHALLAN', 'challan');
define('TAG', 'tag');
define('PRINT_SEND', 'send');
define('PRINT_RECEIVED', 'received');
define('PAGINATION', 25);
define('PAGINATION_XL_PDF', 500);
define('FABRIC_NATURE', [1 => 'Knit Finish Fabric', 2 => 'Woven Fabric']);
define('ACTIVE_CLASS_NAME', 'active');
define('SUCCESS_MSG', 'Success!');
define('SOMETHING_WENT_WRONG', 'Something Went Wrong!');
const ID_AS_VALUE = 'id as value';
const NAME_AS_TEXT = 'name as text';
const STYLE_FILTER_LABELS = [
    1 => 'Style',
    2 => 'Booking No',
    3 => 'Reference No'
];
/**
 * Get Current user object
 */
if (!function_exists('currentUser')) {
    function currentUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }

        return null;
    }
}

if (!function_exists('makeMoneyFormat')) {
    function makeMoneyFormat($amount): string
    {
        try {
            return (new MoneyFormatter())->enMoney($amount);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

if (!function_exists('localizedFor')) {
    function localizedFor($heading): string
    {
        $localized = \Illuminate\Support\Facades\Cache::get('localizations');
        return $localized[$heading] ?? $heading;
    }
}


// return user id of current user
function userId()
{
    if (Auth::check()) {
        return Auth::user()->id;
    }

    return null;
}

// return factory id of session user
function factoryId()
{
    if (Auth::check()) {
        $factoryId = Session::get('factoryId') ?? auth()->user()->factory_id;
    } else {
        $factory = DB::table('factories')->whereNull('deleted_at')->first();
        $factoryId = $factory ? $factory->id : null;
    }

    return $factoryId ?? null;
}

if (!function_exists('associateFactories')) {
    function associateFactories()
    {
        if (auth()->check()) {

            if (!Session::exists('associate_factories')) {
                Session::put('associate_factories', Factory::find(auth()->user()->factory_id)->associate_factories ?? []);
            }
            return Session::get('associate_factories');
        }
        return [];
    }
}

// return dashboard version of session user
if (!function_exists('getDashboardVersion')) {
    function getDashboardVersion()
    {
        if (Auth::check()) {
            return Session::get('dashboardVersion') ?? auth()->user()->dashboard_version;
        }

        return null;
    }
}

// return factory id of current user
function userFactoryId()
{
    if (Auth::check()) {
        return Auth::user()->factory_id;
    }

    return null;
}

if (!function_exists('getRole')) {
    function getRole()
    {
        if (Auth::check()) {
            $user_role = Session::get('user_role');
        }

        return $user_role ?? '';
    }
}

if (!function_exists('getDept')) {
    function getDept(): string
    {
        if (Auth::check()) {
            $dept_name = Session::get('dept_name');
        }

        return $dept_name ?? '';
    }
}

if (!function_exists('groupName')) {
    function groupName()
    {
        if (Auth::check()) {
            return \SkylarkSoft\GoRMG\SystemSettings\Models\User::with('factory')->findOrFail(Auth::id())->factory->group_name ?? '';
        } elseif (request()->has('factory_id')) {
            return DB::table('factories')->where('id', request()->get('factory_id'))->first()->group_name ?? '';
        }

        return null;
    }
}

if (!function_exists('factoryName')) {
    function factoryName()
    {
        if (Auth::check()) {
            $factory = Session::get('userFactoryName');
            return $factory->factory_name ?? null;
        }

        return null;
    }
}

if (!function_exists('factoryNameBn')) {
    function factoryNameBn()
    {
        if (Auth::check()) {
            $factory = Session::get('userFactoryName');
            return $factory->factory_name_bn ?? null;
        }

        return null;
    }
}

if (!function_exists('factoryImage')) {
    function factoryImage()
    {
        if (Auth::check()) {
            $factory = Session::get('userFactoryName');
            return session()->get('factory_image') ?? $factory->factory_image ?? null;
        }

        return null;
    }
}

if (!function_exists('sessionFactoryName')) {
    function sessionFactoryName()
    {
        if (Auth::check()) {
            return DB::table('factories')->where('id', Session::get('factoryId'))->first()->factory_name ?? '';
        } elseif (request()->has('factory_id')) {
            return DB::table('factories')->where('id', request()->get('factory_id'))->first()->factory_name ?? '';
        }

        return null;
    }
}

if (!function_exists('factoryAddress')) {
    function factoryAddress()
    {
        if (Auth::check()) {
            $factoryDetails = Session::get('userFactoryName');
            return $factoryDetails->factory_address ?? '';
        }

        return null;
    }
}

if (!function_exists('factoryAddressBn')) {
    function factoryAddressBn()
    {
        if (Auth::check()) {
            $factory = Session::get('userFactoryName');
            return $factory->factory_address_bn ?? '';
        }

        return null;
    }
}

if (!function_exists('factoryPhone')) {
    function factoryPhone()
    {
        if (Auth::check()) {
            return Factory::query()->findOrFail(factoryId())->phone_no ?? '';
        }

        return null;
    }
}

if (!function_exists('groupName')) {
    function groupName()
    {
        if (Auth::check()) {
            return \SkylarkSoft\GoRMG\SystemSettings\Models\User::with('factory')->findOrFail(Auth::id())->factory->group_name ?? '';
        } elseif (request()->has('factory_id')) {
            return DB::table('factories')->where('id', request()->get('factory_id'))->first()->group_name ?? '';
        }

        return null;
    }
}


if (!function_exists('sessionFactoryAddress')) {
    function sessionFactoryAddress()
    {
        if (Auth::check()) {
            return session()->get('factory_address') ?? preg_replace('/[^A-Za-z0-9\-,. ]/', ' ', \SkylarkSoft\GoRMG\SystemSettings\Models\Factory::whereId(Session::get('factoryId'))->first()->factory_address) ?? '';
        }

        return null;
    }
}

if (!function_exists('setActiveClass')) {
    function setActiveClass($path): string
    {
        if ($path === request()->segment(1)) {
            return ACTIVE_CLASS_NAME;
        } else {
            return '';
        }
    }

    ;
}

if (!function_exists('setActiveClassForFullPath')) {
    function setActiveClassForFullPath($path): string
    {
        if ($path == \Illuminate\Support\Facades\Request::is($path)) {
            return ACTIVE_CLASS_NAME;
        } else {
            return '';
        }
    }

    ;
}

if (!function_exists('setMultipleActiveClass')) {
    function setMultipleActiveClass($paths): string
    {
        $segments = collect(request()->segments())
            ->filter(function ($segment) {
                return $segment !== "#";
            })->implode('/');
        dump($paths);
        if (in_array(request()->segment(1), $paths)) {
            return ACTIVE_CLASS_NAME;
        } else {
            return '';
        }
    }
}

if (!function_exists('setDynamicSingleActiveClass')) {
    function setDynamicSingleActiveClass($element): ?string
    {
        $element = $element[0] !== '/' ? '/' . $element : $element;
        $segments = "/" . collect(request()->segments())
                ->filter(function ($segment) {
                    return $segment !== "#";
                })->implode('/');

        if ($element === $segments) {
            return ACTIVE_CLASS_NAME;
        } else {
            return null;
        }

        //        if (strpos($element, '/') !== false) {
        //            if (strstr($element, '/', true) === request()->segment(1)) {
        //                return ACTIVE_CLASS_NAME;
        //            } else {
        //                return '';
        //            }
        //        } else {
        //            if ($element === request()->segment(1)) {
        //                return ACTIVE_CLASS_NAME;
        //            } else {
        //                return '';
        //            }
        //        }
    }
}

if (!function_exists('getMonthName')) {
    function getMonthName($monthNo)
    {
        if (is_numeric($monthNo) && ($monthNo > 0 && $monthNo < 13)) {
            $monthName = date("F", mktime(0, 0, 0, (int)$monthNo, 10));
        } else {
            $monthName = 'N/A';
        }

        return $monthName;
    }
}


if (!function_exists('has_permission')) {
    function has_permission($menu, $can)
    {
        return (Session::has('permission_of_' . implode('_', explode(' ', strtolower($menu))) . '_' . $can) ||
            getRole() == 'super-admin' ||
            getRole() == 'admin');
    }
}

if (!function_exists('calculateDays')) {
    function calculateDays($start, $end)
    {
        if (!$start || !$end) {
            return null;
        }

        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        $days = $startDate->diffInDays($endDate, false) + 1;

        return $days;
    }
}

if (!function_exists('operationDate')) {
    function operationDate()
    {
        return now()->toDateString();
    }
}

if (!function_exists('operationDateTime')) {
    function operationDateTime()
    {
        return now()->toDateTimeString();
    }
}

if (!function_exists('PAD')) {
    function PAD($number): string
    {
        return str_pad($number, 2, "0", STR_PAD_LEFT);
    }
}

if (!function_exists('imageEncode')) {
    function imageEncode()
    {
        $image = file_get_contents(public_path('/modules/skeleton/flatkit/assets/images/company-image.png'));
        if (factoryImage() && \Storage::disk('public')->exists('factory_image/' . factoryImage())) {
            $image = file_get_contents(public_path('/storage/factory_image/' . factoryImage()));
        }
        return base64_encode($image);
    }
}

if (!function_exists('getProductionEntryType')) {
    function getProductionEntryType()
    {
        $production_entry_type = 1;
        if (!session()->get('production_entry_type') && Schema::hasTable('garments_production_entries')) {
            $garments_production_variable = DB::table('garments_production_entries')->where('factory_id', factoryId())->first();
            $production_entry_type = $garments_production_variable ? (collect($garments_production_variable)->has('entry_type') ? $garments_production_variable->entry_type : 1) : 1;
        } elseif (session()->get('production_entry_type')) {
            $production_entry_type = session()->get('production_entry_type');
        }
        return $production_entry_type;
    }
}

if (!function_exists('getPercentage')) {
    function getPercentage($first_num, $second_num)
    {
        if ($second_num != 0) {
            return ($first_num * 100) / $second_num;
        }

        return 0;
    }
}


if (!function_exists('getLastNDays')) {
    function getLastNDays($days, $format = 'd/m'): array
    {
        $m = date("m");
        $de = date("d");
        $y = date("Y");
        $dateArray = array();
        for ($i = 0; $i <= $days - 1; $i++) {
            $dateArray[] = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
        }
        return array_reverse($dateArray);
    }
}


if (!function_exists('companyName')) {
    function companyName()
    {
        if (auth()->check()) {
            return session()->get('companyName') ?? auth()->user()->factory->name;
        }
        return null;
    }
}

if (!function_exists('companyAddress')) {
    function companyAddress()
    {
        if (auth()->check()) {
            return session()->get('companyAddress') ?? auth()->user()->factory->address;
        }
        return null;
    }
}

if (!function_exists('getbundleCardSerial')) {
    function getbundleCardSerial()
    {
        if (auth()->check()) {
            return session()->get('bundle_card_serial') ?? DB::table('garments_production_entries')->where('factory_id', factoryId())->first()->bundle_card_serial ?? 'bundle_no';
        }
        return 'bundle_no';
    }
}

if (!function_exists('getStyleFilterOption')) {
    function getStyleFilterOption()
    {
        if (auth()->check()) {
            return session()->get('style_filter_option') ?? DB::table('garments_production_entries')->where('factory_id', factoryId())->first()->style_filter_option ?? 1;
        }
        return 1;
    }
}

if (!function_exists('getStyleFilterOptionValue')) {
    function getStyleFilterOptionValue()
    {
        $style_filter_option = getStyleFilterOption();
        return array_key_exists($style_filter_option, STYLE_FILTER_LABELS) ? STYLE_FILTER_LABELS[$style_filter_option] : STYLE_FILTER_LABELS[1];
    }
}

if (!function_exists('getBundleCardPrintStyle')) {
    function getBundleCardPrintStyle()
    {
        if (auth()->check()) {
            return session()->get('bundle_card_print_style') ?? DB::table('garments_production_entries')->where('factory_id', factoryId())->first()->bundle_card_print_style ?? 0;
        }
        return 0;
    }
}

if (!function_exists('getBundleCardStickerStyles')) {
    function getBundleCardStickerStyles()
    {
        $width = 65;
        $height = 35;
        $font_size = 11;
        $max_width = 21.5;
        $max_height = 12.75;
        $barcode_width = 1.7;
        $barcode_height = 27;
        if (auth()->check()) {
            $query = DB::table('garments_production_entries')->where('factory_id', factoryId())->first();
            $width = $query ? $query->bundle_card_sticker_width : 65;
            $height = $query ? $query->bundle_card_sticker_height : 35;
            $font_size = $query ? $query->bundle_card_sticker_font_size : 11;
            $max_width = $query ? $query->bundle_card_sticker_max_width : 21.5;
            $barcode_width = $query ? ($query->barcode_width ?? $barcode_width) : $barcode_width;
            $barcode_height = $query ? ($query->barcode_height ?? $barcode_height) : $barcode_height;
        }
        return [
            'width' => $width,
            'height' => $height,
            'font_size' => $font_size,
            'max_width' => $max_width,
            'max_height' => $max_height,
            'barcode_height' => $barcode_height,
            'barcode_width' => $barcode_width,
        ];
    }
}

if (!function_exists('getBundleCardSuffixStyle')) {
    function getBundleCardSuffixStyle()
    {
        if (auth()->check()) {
            return session()->get('bundle_card_suffix_style') ?? DB::table('garments_production_entries')->where('factory_id', factoryId())->first()->bundle_card_suffix_style ?? 0;
        }
        return 0;
    }
}

if (!function_exists('getBundleCardStickerRatioViewStatus')) {
    function getBundleCardStickerRatioViewStatus()
    {
        if (auth()->check()) {
            return session()->get('bundle_card_sticker_ratio_view_status') ?? DB::table('garments_production_entries')->where('factory_id', factoryId())->first()->bundle_card_sticker_ratio_view_status ?? 0;
        }
        return 0;
    }
}

if (!function_exists('getErpMenuViewStatus')) {
    function getErpMenuViewStatus()
    {
        if (auth()->check()) {
            return session()->get('erp_menu_view_status') ?? DB::table('garments_production_entries')->where('factory_id', factoryId())->first()->erp_menu_view_status ?? 1;
        }
        return 0;
    }
}
if (!function_exists('getScanDataCachingTime')) {
    function getScanDataCachingTime()
    {
        if (auth()->check()) {
            return session()->get('scan_data_caching_time') ?? DB::table('garments_production_entries')->where('factory_id', factoryId())->first()->scan_data_caching_time ?? 86400;
        }
        return 0;
    }
}

if (!function_exists('getBundleCardStraightSerialMaxLimit')) {
    function getBundleCardStraightSerialMaxLimit()
    {
        if (auth()->check()) {
            return session()->get('bundle_straight_serial_max_limit') ?? DB::table('garments_production_entries')->where('factory_id', factoryId())->first()->bundle_straight_serial_max_limit ?? 10000;
        }
        return 0;
    }
}

if (!function_exists('getLineWiseHourShowData')) {
    function getLineWiseHourShowData()
    {
        if (auth()->check()) {
            $lineWiseHourShowData = session()->get('line_wise_hour_show') ?? GarmentsProductionEntry::DEFAULT_LINE_WISE_HOURS;
        } else {
            $lineWiseHourShowData = GarmentsProductionEntry::DEFAULT_LINE_WISE_HOURS;
            if (Schema::hasTable('garments_production_entries')) {
                $garments_production_variable = DB::table('garments_production_entries')->where('factory_id', factoryId())->first();
                $lineWiseHourShowData = $garments_production_variable && collect($garments_production_variable)->has('line_wise_hour_show') ? json_decode($garments_production_variable->line_wise_hour_show, true) : $line_wise_hour_show;
            }
        }
        return $lineWiseHourShowData;
    }
}

if (!function_exists('getBundleCardSuffixOptions')) {
    function getBundleCardSuffixOptions()
    {
        $suffixes = getBundleCardSuffixNumbers();
        if (getBundleCardSuffixStyle()) {
            $suffixes = getBundleCardSuffixLetters();
        }
        return $suffixes;
    }
}

if (!function_exists('getBundleCardSuffixNumbers')) {
    function getBundleCardSuffixNumbers()
    {
        $suffix = [];
        for ($i = 1; $i <= 100; $i++) {
            $suffix[$i] = '(' . $i . ')';
        }
        return $suffix;
    }
}

if (!function_exists('getBundleCardSuffixLetters')) {
    function getBundleCardSuffixLetters()
    {
        return [
            'A' => '(A)', 'B' => '(B)', 'C' => '(C)',
            'D' => '(D)', 'E' => '(E)', 'F' => '(F)',
            'G' => '(G)', 'H' => '(H)', 'I' => '(I)',
            'J' => '(J)', 'K' => '(K)', 'L' => '(L)',
            'M' => '(M)', 'N' => '(N)', 'O' => '(O)',
            'P' => '(P)', 'Q' => '(Q)', 'R' => '(R)',
            'S' => '(S)', 'T' => '(T)', 'U' => '(U)',
            'V' => '(V)', 'W' => '(W)', 'X' => '(X)',
            'Y' => '(Y)', 'Z' => '(Z)'
        ];
    }
}

if (!function_exists('getYarnStoreStickerStyles')) {
    function getYarnStoreStickerStyles()
    {
        $barcode_width = 2.98;
        $barcode_height = 58;
        $barcode_font_size = 14;
        $barcode_container_m_top = 10;
        $barcode_container_m_left = 10;
        $barcode_container_m_right = 10;
        $barcode_container_m_bottom = 10;
        $barcode_container_p_top = 0;
        $barcode_container_p_left = 0;
        $barcode_container_p_right = 0;
        $barcode_container_p_bottom = 0;
        if (auth()->check()) {
            $query = DB::table('garments_production_entries')->where('factory_id', factoryId())->first();
            $barcodeData = $query && collect($query)->has('yarn_store_barcode_meta') ? json_decode($query->yarn_store_barcode_meta, true) : GarmentsProductionEntry::DEFAULT_YARN_STORE_BARCODE_META;
            $barcode_width = (array_key_exists('barcode_width', $barcodeData) && $barcodeData['barcode_width']) ? $barcodeData['barcode_width'] : 2.98;
            $barcode_height = (array_key_exists('barcode_height', $barcodeData) && $barcodeData['barcode_height']) ? $barcodeData['barcode_height'] : 58;
            $barcode_font_size = (array_key_exists('barcode_font_size', $barcodeData) && $barcodeData['barcode_font_size']) ? $barcodeData['barcode_font_size'] : 14;
            $barcode_container_m_top = (array_key_exists('barcode_container_m_top', $barcodeData) && $barcodeData['barcode_container_m_top']) ? $barcodeData['barcode_container_m_top'] : 10;
            $barcode_container_m_left = (array_key_exists('barcode_container_m_left', $barcodeData) && $barcodeData['barcode_container_m_left']) ? $barcodeData['barcode_container_m_left'] : 10;
            $barcode_container_m_right = (array_key_exists('barcode_container_m_right', $barcodeData) && $barcodeData['barcode_container_m_right']) ? $barcodeData['barcode_container_m_right'] : 10;
            $barcode_container_m_bottom = (array_key_exists('barcode_container_m_bottom', $barcodeData) && $barcodeData['barcode_container_m_bottom']) ? $barcodeData['barcode_container_m_bottom'] : 10;
            $barcode_container_p_top = (array_key_exists('barcode_container_p_top', $barcodeData) && $barcodeData['barcode_container_p_top']) ? $barcodeData['barcode_container_p_top'] : 0;
            $barcode_container_p_left = (array_key_exists('barcode_container_p_left', $barcodeData) && $barcodeData['barcode_container_p_left']) ? $barcodeData['barcode_container_p_left'] : 0;
            $barcode_container_p_right = (array_key_exists('barcode_container_p_right', $barcodeData) && $barcodeData['barcode_container_p_right']) ? $barcodeData['barcode_container_p_right'] : 0;
            $barcode_container_p_bottom = (array_key_exists('barcode_container_p_bottom', $barcodeData) && $barcodeData['barcode_container_p_bottom']) ? $barcodeData['barcode_container_p_bottom'] : 0;
        }
        return [
            'barcode_width' => $barcode_width,
            'barcode_height' => $barcode_height,
            'barcode_font_size' => $barcode_font_size,
            'barcode_container_m_top' => $barcode_container_m_top,
            'barcode_container_m_left' => $barcode_container_m_left,
            'barcode_container_m_right' => $barcode_container_m_right,
            'barcode_container_m_bottom' => $barcode_container_m_bottom,
            'barcode_container_p_top' => $barcode_container_p_top,
            'barcode_container_p_left' => $barcode_container_p_left,
            'barcode_container_p_right' => $barcode_container_p_right,
            'barcode_container_p_bottom' => $barcode_container_p_bottom,
        ];
    }
}

if (!function_exists('getInactiveUrlsData')) {
    function getInactiveUrlsData()
    {
        if (session()->get('inactive_urls')) {
            $inactiveUrls = session()->get('inactive_urls');
        } else {
            $inactiveUrls = [];
            $erpMenuViewStatus = getErpMenuViewStatus();
            if (Schema::hasTable('application_menu_active_data') && $erpMenuViewStatus == 5) {
                $applicationMenuActiveData = DB::table('application_menu_active_data')->first();
                $inactiveUrls = $applicationMenuActiveData && $applicationMenuActiveData->inactive_urls ? json_decode($applicationMenuActiveData->inactive_urls, true) : [];
            }
        }
        return $inactiveUrls;
    }
}


if (!function_exists('numberFormat')) {
    function numberFormat($number, $thousandSeparator = false)
    {
        if ($thousandSeparator) {
            return number_format($number, 2);
        }
        return number_format($number, 2, '.', '');
    }
}

if (!function_exists('disableFieldForApproval')) {
    function disableFieldForApproval($data): bool
    {
        return isset($data) && $data->step > 0 && $data->rework_status == 0;
    }
}

if (!function_exists('BdtNumFormat')) {
    function BdtNumFormat($number, $decimal = 2)
    {
        $number = number_format($number, $decimal, '.', '');
        $number = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $number);
        return $number;

    }
}

if (!function_exists('isCuttingQtyValidationEnabled')) {
    function isCuttingQtyValidationEnabled()
    {
        if (auth()->check()) {
            return session()->get('cutting_qty_validation')
                ?? DB::table('garments_production_entries')
                ->where('factory_id', factoryId())
                ->first()->cutting_qty_validation ?? 0;
        }
        return 0;
    }
}

if (!function_exists('isFabricConsApprovalEnabled')) {
    function isFabricConsApprovalEnabled()
    {
        if (auth()->check()) {
            return session()->get('fabric_cons_approval')
                ?? DB::table('garments_production_entries')
                ->where('factory_id', factoryId())
                ->first()->fabric_cons_approval ?? 0;
        }
        return 0;
    }
}

if (!function_exists('isMaxBundleQtyEnabled')) {
    function isMaxBundleQtyEnabled()
    {
        if (auth()->check()) {
            return session()->get('max_bundle_qty')
                ?? DB::table('garments_production_entries')
                ->where('factory_id', factoryId())
                ->first()->max_bundle_qty ?? 0;
        }
        return 0;
    }
}

if (!function_exists('finishingReportVariable')) {
    function finishingReportVariable()
    {
        if (auth()->check()) {
            return session()->get('finishing_report')
                ?? DB::table('garments_production_entries')
                ->where('factory_id', factoryId())
                ->first()->finishing_report ?? 0;
        }
        return 0;
    }
}

if (!function_exists('getHeadBanner')) {
    function getHeadBanner($requestPath = null): array
    {
        $requestPath = preg_replace("/\d+/", "{id}", $requestPath);
        return PackageConst::ROUTE_LOOKUP[$requestPath] ?? [];
    }
}
